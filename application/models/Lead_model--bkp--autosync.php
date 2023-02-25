<?php
class Lead_model extends CI_Model
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

		// if($this->fsas_db->database=='' || $this->client_db->database==''){
		// 	if($this->fsas_db->database==''){
		// 		die('Main DB error!');
		// 	}
		// 	if($this->client_db->database==''){
		// 		die('Client DB error!');
		// 	}			
		// }
    } 
	public function GetLeadListAll($search_data=array())
	{		
		/*echo '<pre>';
		print_r($search_data);*/
		$subsql='';
		if($search_data['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$search_data['assigned_user'].")";
		}

		if($search_data['opportunity_stage']!='')
		{
			$subsql.=" AND lead.current_stage_id=".$search_data['opportunity_stage'];
		}
		
		if($search_data['opportunity_status']!='')
		{
			if($search_data['opportunity_status']=='Y')
			{
				$subsql.=" AND lead.is_hotstar='Y'";
			}
			else
			{
				$subsql.=" AND lead.current_status_id=".$search_data['opportunity_status'];
			}
			
		}
		
		if($search_data['lead_to_date']!='' && $search_data['lead_from_date']!='')
		{
			$from = date_display_format_to_db_format($search_data['lead_from_date']);  
			$to = date_display_format_to_db_format($search_data['lead_to_date']);  
			$subsql.=" AND (lead.enquiry_date BETWEEN '".$from."' AND '".$to."') ";
		}

		if($search_data['search_keyword']!='')
		{
			$subsql.=" AND lead.title LIKE '%".$search_data['search_keyword']."%'";
		}

		$sql="SELECT 
			COUNT(lead_opportunity.lead_id) AS proposal,
			lead.*,
			user.name AS user_name,
			cus.first_name AS cus_first_name,
			cus.last_name AS cus_last_name,
			cus.mobile AS cus_mobile,
			cus.email AS cus_email,
			cus.company_name AS cus_company_name,
			source.name AS source_name			
			FROM lead INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			INNER JOIN source ON source.id=lead.source_id 
			INNER JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN lead_opportunity ON lead_opportunity.lead_id=lead.id WHERE lead.status='1' $subsql GROUP BY lead.id ORDER BY lead.id DESC ";

		$result=$this->client_db->query($sql);
		//echo $sql;
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}
	
	function CreateLead($data,$client_info=array())
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
		if($this->client_db->insert('lead',$data))
   		{			
           return $this->client_db->insert_id();
   		}
   		else
   		{
			//echo $last_query = $this->client_db->last_query();die('ok');
          	return false;
   		}
	}

	function create_lead_assigned_user_log($data,$client_info=array())
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
		if($this->client_db->insert('lead_assigned_user_log',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}


	function CreateLeadComment($data)
	{

		if($this->client_db->insert('lead_comment',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function GetLeadCommentListAll($lead_id,$user_id='',$client_info=array())
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

		$subdql="";
		if($user_id)
		{
			$subdql .=" AND lead_comment.user_id='".$user_id."'";
		}
		$sql="select 
			lead_comment.id,
			lead_comment.lead_id,
			lead_comment.lead_opportunity_id,
			lead_comment.source_id,
			lead_comment.comment,
			lead_comment.mail_trail_html,
			lead_comment.mail_trail_ids,
			lead_comment.website,
			lead_comment.cc_to_employee,
			lead_comment.mail_to_client,
			lead_comment.mail_to_client_from_mail,
			lead_comment.mail_to_client_from_name,
			lead_comment.regret_mail_from_mail,
			lead_comment.regret_mail_from_name,
			lead_comment.sent_mail_quotation_to_client_from_mail,
			lead_comment.sent_mail_quotation_to_client_from_name,
			lead_comment.mail_subject_of_sent_quotation_to_client,
			lead_comment.mail_subject_of_update_lead_mail_to_client,
			lead_comment.mail_subject_of_update_lead_regret_this_lead,
			lead_comment.attach_file,
			lead_comment.communication_type,
			lead_comment.next_followup_date,
			lead_comment.create_date,
			lead_comment.user_id,
			lead_comment.ip_address,
			lead_comment.title,
			quo.id AS quotation_id,
			quo.opportunity_title AS quotation_title,
			lead.title as lead_title,
			lead.customer_id as lead_customer_id,
			lead.assigned_user_id,
			lead.source_id,
			user.name as user_name,
			u2.name AS updated_by,
			cus.first_name as cus_first_name,
			cus.last_name as cus_last_name,
			cus.mobile as cus_mobile,
			source.name as source_name,
			source.alias_name as source_alias_name
			FROM lead_comment 
			LEFT JOIN lead on lead_comment.lead_id=lead.id
			LEFT JOIN lead_opportunity AS quo on lead_comment.lead_opportunity_id=quo.id 
			INNER JOIN customer as cus on cus.id=lead.customer_id 
			INNER JOIN source on source.id=lead.source_id 
			LEFT JOIN user on user.id=lead.assigned_user_id
			LEFT JOIN user AS u2 on u2.id=lead_comment.user_id 
			WHERE lead_comment.lead_id='".$lead_id."' $subdql
			ORDER BY lead_comment.id DESC";

		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
	
	public function GetLeadData($id,$client_info=array())
	{
		// if($this->class_name=='cronjobs' || $this->class_name=='capture')
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
		$sql="select 
				lead.id,
				lead.title,
				lead.customer_id,
				lead.source_id,
				lead.assigned_user_id,
				lead.assigned_observer,
				lead.buying_requirement,
				lead.attach_file,
				lead.description,
				lead.enquiry_date,
				lead.followup_date,
				lead.is_followup_date_changed,
				lead.followup_type_id,
				lead.create_date,
				lead.modify_date,
				lead.assigned_date,
				lead.status,
				lead.current_stage_id,
				lead.current_stage,
				lead.current_stage_wise_msg,
				lead.current_status_id,
				lead.current_status,
				lead.is_hotstar,
				lead.lost_reason,
				lead.im_query_id,
				lead.im_setting_id,
				lead.fb_ig_id,
				source.name as source_name,
				user.name as user_name,
				user.mobile AS user_mobile,
				user.email AS user_email,
				user.gender as user_gender,
				cus.id as cus_id,
				cus.country_id as country_id,
				cus.state as state,
				cus.city as city,
				cus.first_name as cus_first_name,
				cus.last_name as cus_last_name,
				cus.contact_person AS cus_contact_person,
				cus.mobile_country_code AS cus_mobile_country_code,
				cus.mobile as cus_mobile,
				cus.alt_mobile as cus_alt_mobile,
				cus.email as cus_email,
				cus.alt_email as cus_alt_email,
				cus.office_phone as cus_office_phone,
				cus.website as cus_website,
				cus.company_name as cus_company_name,
				cus.address as cus_address,
				cus.zip as cus_zip,
				cus.gst_number AS cus_gst_number,
				countries.name as cus_country,
				countries.phonecode AS cus_country_code,
				states.name as cus_state,cities.name as cus_city,
				tbl_renewal_details.id AS renewal_detail_id,
				tbl_renewal_details.renewal_id FROM lead 
				INNER JOIN customer as cus on cus.id=lead.customer_id 
				LEFT JOIN countries on countries.id=cus.country_id 
				LEFT JOIN states on states.id=cus.state 
				LEFT JOIN cities on cities.id=cus.city 
				LEFT JOIN source on source.id=lead.source_id 
				LEFT JOIN user on user.id=lead.assigned_user_id 
				LEFT JOIN tbl_renewal_details on tbl_renewal_details.lead_id=lead.id
				WHERE lead.id='".$id."'";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
		
	}
	
	
	function get_lead_last_updated_date($lid)
	{
		$sql="select create_date FROM lead_comment WHERE lead_id='".$lid."' ORDER BY id DESC LIMIT 1";
		$result=$this->client_db->query($sql);
		$row=$result->row_array();
		if($row){
			return $row['create_date'];
		}
		else{
			return '';
		}
		
	}
	
	function UpdateLead($data,$id,$client_info=array())
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
		$this->client_db->where('id',$id);
		if($this->client_db->update('lead',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function DeleteLead($id)
	{
		$data=array('status'=>'2');
		$this->client_db->where('id',$id);

		if($this->client_db->update('lead',$data))
		{
			return true;
		}
		else
		{
			return false;
		}		

	}
	public function GetCurrencyList($client_info=array())
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

		$sql="SELECT id,name,code FROM currency";
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
	public function GetCommunicationList($client_info=array())
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
		
		$sql="SELECT id,title,title1,title2 FROM communication_master";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	// =======================================================================
	// DASHBOARD SUMMARY REPORT

	public function get_lead_count($user_type,$user_id)
	{
		$subsql = '';
		if(strtolower($user_type)=='user')
		{
			$subsql .= ' AND l.assigned_user_id='.$user_id;
		}
		$sql = "SELECT count(*) AS total_active,
				SUM(if(current_status_id=2,1,0)) AS total_hot,
				SUM(if(current_status_id=1,1,0)) AS total_worm,
				SUM(if(current_stage_id=6,1,0)) AS total_auto_reg,
				SUM(if(current_stage_id=7,1,0)) AS total_auto_deal_lost,
				SUM(if(current_stage_id=1,1,0)) AS total_pending,
				SUM(if(current_stage_id=4,1,0)) AS total_deal_won,
				SUM(if(current_stage_id=5,1,0)) AS total_deal_lost
				FROM lead AS l WHERE l.status='1' $subsql";
		//echo $sql;
		$result=$this->client_db->query($sql);
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	public function get_lead_list_summery($user_type,$user_id)
	{
		$arr=array();
		$subsql = '';
		if(strtolower($user_type)=='user')
		{
			$subsql .= ' AND l.assigned_user_id='.$user_id;
		}

		// Latest Leads (20)
		$sql = "SELECT * FROM lead AS l WHERE l.status='1' $subsql ORDER BY id DESC LIMIT 0,20";
		$query=$this->client_db->query($sql);
		$result =  $query->result_array();
		$arr['latest_leads_20']=$result;

		// Today's followups 
		$sql2 = "SELECT * FROM lead AS l WHERE l.status='1' AND l.followup_date='".date('Y-m-d')."' AND l.current_stage_id IN ('1','2') $subsql";
		$query2=$this->client_db->query($sql2);
		$result2 =  $query2->result_array();
		$arr['todays_followup']=$result2;

		// Auto Lost Leads 
		$sql3 = "SELECT * FROM lead AS l WHERE l.status='1' AND l.current_stage_id='7' $subsql";
		$query3=$this->client_db->query($sql3);
		$result3 =  $query3->result_array();
		$arr['auto_lost_leads']=$result3;

		// Auto Regretted Leads 
		$sql4 = "SELECT * FROM lead AS l WHERE l.status='1' AND l.current_stage_id='6' $subsql";
		$query4=$this->client_db->query($sql4);
		$result4 =  $query4->result_array();
		$arr['auto_regretted_leads']=$result4;

		// Conversion 
		$sql5 = "SELECT COUNT(*) AS source_count,s.name FROM lead AS l
			INNER JOIN source AS s ON l.source_id=s.id WHERE l.status='1' $subsql GROUP BY l.source_id";
		$query5=$this->client_db->query($sql5);
		$result5 =  $query5->result_array();
		$arr['conversion_wise_count']=$result5;

		// Lead Source Analysis
		$sql6 = "SELECT SUM(if(current_stage_id=4,1,0)) AS deal_won_count,SUM(if(current_stage_id=5,1,0)) AS deal_lost_count,SUM(if(current_stage_id=1,1,0)) AS deal_pending_count,s.name,if(rev.total_revenue_count,rev.total_revenue_count,0) AS total_revenue_count FROM lead AS l
			INNER JOIN source AS s ON l.source_id=s.id 
			LEFT JOIN (
				SELECT SUM(opportunity_product.price) AS total_revenue_count,lead_opportunity.lead_id FROM lead_opportunity 
				INNER JOIN lead_opportunity_wise_po ON lead_opportunity.id=lead_opportunity_wise_po.lead_opportunity_id 	
				INNER JOIN opportunity_product ON opportunity_product.opportunity_id=lead_opportunity.id		
			) AS rev ON rev.lead_id=l.id AND l.current_stage_id='4'
			WHERE l.status='1' $subsql GROUP BY l.source_id";
		//echo $sql6;
		$query6=$this->client_db->query($sql6);
		$result6 =  $query6->result_array();
		$arr['source_analysis_count']=$result6;

		return $arr;
	}

	public function active_leads_summary($user_type,$user_id)
	{
		$subsql = '';
		if(strtolower($user_type)=='user')
		{
			$subsql .= ' AND l.assigned_user_id='.$user_id;
		}
		// $sql = "SELECT COUNT(*) AS total_active_leads FROM lead AS l 
		// 		LEFT JOIN lead_comment lc ON l.id=lc.lead_id WHERE l.status='1' $subsql";
		//$result=$this->client_db->query($sql);
		//$result =  $result->row_array();

		$sql = "SELECT COUNT(*) AS total_active_leads FROM lead AS l WHERE l.status='1' $subsql";
		$result=$this->client_db->query($sql);
		$result =  $result->row_array();

		$sql2 = "SELECT COUNT(A.total_action_leads) AS total_action_leads FROM 
				( 
				SELECT COUNT(l.id) AS total_action_leads,group_concat(lc.id) FROM lead AS l 
				INNER JOIN lead_comment lc ON l.id=lc.lead_id 
				WHERE l.status='1' $subsql GROUP BY lc.lead_id HAVING COUNT(l.id)>1
				) A";
		$result2=$this->client_db->query($sql2);		
		$row2 =  $result2->row_array();	


		$result = array('total_active_leads'=>$result['total_active_leads'],
						'total_action_leads'=>$row2['total_action_leads']
						);

		return $result;
	}
	
	public function followup_summary_last_7days($user_type,$user_id,$frm_date,$to_date)
	{
		$subsql = '';
		if(strtolower($user_type)=='user')
		{
			$subsql .= ' AND l.assigned_user_id='.$user_id;
		}
		$sql = "SELECT COUNT(A.total_followup) AS total_followup FROM 
				( 
				SELECT COUNT(l.id) AS total_followup,group_concat(lc.id) FROM lead AS l 
				INNER JOIN lead_comment lc ON l.id=lc.lead_id 
				WHERE l.status='1' $subsql AND lc.create_date BETWEEN '".$frm_date."' AND '".$to_date."' GROUP BY lc.lead_id HAVING COUNT(l.id)>0
				) A";

		$result=$this->client_db->query($sql);
		if($result){
			$result =  $result->row_array();
		}
		

		$sql2 = "SELECT COUNT(A.total_not_followup) AS total_not_followup FROM 
				( 
				SELECT COUNT(l.id) AS total_not_followup,group_concat(lc.id) FROM lead AS l 
				INNER JOIN lead_comment lc ON l.id=lc.lead_id 
				WHERE l.status='1' $subsql AND lc.create_date < '".$frm_date."' GROUP BY lc.lead_id HAVING COUNT(l.id)>0
				) A";
		$result2=$this->client_db->query($sql2);		
		
		if($result2){
			$result2 =  $result2->row_array();
		}	

		if(isset($result['total_followup']) && isset($result2['total_not_followup'])){
			$result = array('followup'=>$result['total_followup'],
						'not_followup'=>$result2['total_not_followup']
						);
		}
		else{
			$result = array('followup'=>'',
						'not_followup'=>''
						);
		}	

		return $result;
	}
	public function converted_leads_summary($user_type,$user_id)
	{
		return 0;
		$subsql = '';
		if(strtolower($user_type)=='user')
		{
			$subsql .= ' AND l.assigned_user_id='.$user_id;
		}
		$sql = "SELECT COUNT(*) AS total_count,SUM(if(lo.opportunity_stage=8,1,0)) AS converted_count,
				SUM(if(lo.opportunity_stage=9,1,0)) AS not_converted_count 
				FROM lead AS l 
				INNER JOIN lead_opportunity AS lo ON l.id=lo.lead_id 
				WHERE l.status='1' $subsql";
		
		$result=$this->client_db->query($sql);
		//return $result->row_array();
		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	// ---------------------------
	// My Calendar / Appointments
	public function my_calender_summary($user_type,$user_id,$to_date)
	{
		$today_date  = date('Y-m-d');
		$subsql = "";
		$subsql .= " AND lo.followup_date>='".$today_date."'";
		if(strtolower($user_type)=='user')
		{
			$subsql .= ' AND l.assigned_user_id='.$user_id;
		}

		$sql = "SELECT l.title,lo.opportunity_title,lo.followup_date FROM lead AS l 
				INNER JOIN lead_opportunity AS lo ON l.id=lo.lead_id
				WHERE l.status=1 $subsql ORDER BY lo.followup_date DESC LIMIT 0,5";

		$result=$this->client_db->query($sql);
		//return $result->result_array();

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	// My Calendar / Appointments
	// ---------------------------

	

	// DASHBOARD SUMMARY REPORT
	// ========================================================================

	public function last_lead_comment($lead_id)
	{
		$sql="SELECT * FROM lead_comment WHERE lead_id='".$lead_id."'";
		$query=$this->client_db->query($sql);
		if($query){
			if($query->num_rows()>1)
			{
				$sql2="SELECT * FROM lead_comment WHERE lead_id='".$lead_id."' ORDER BY id DESC LIMIT 1,1";
			}
			else
			{
				$sql2="SELECT * FROM lead_comment WHERE lead_id='".$lead_id."' ORDER BY id DESC LIMIT 0,1";
			}
			$result=$this->client_db->query($sql2);
			//return $result->row();
			if($result){
				return $result->row();
			}
			else{
				return (object)array();
			}
		}
		else{
			return (object)array();
		}				
	}

	public function get_leads_by_cpmpany($cid,$client_info=array())
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

		$sql="SELECT 
		id,
		title,
		customer_id,
		assigned_user_id,
		buying_requirement,
		enquiry_date,
		followup_date,
		modify_date,
		current_stage_id,			
		current_stage,
		current_status,
		is_hotstar
		 FROM lead WHERE customer_id='".$cid."'";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
		

	}

	function CreateLeadStageLog($data,$client_info=array())
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
		if($this->client_db->insert('lead_stage_log',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	function CreateLeadStatusLog($data,$client_info=array())
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
		if($this->client_db->insert('lead_status_log',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	public function get_prev_stage($lid,$client_info=array())
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

		$sql="SELECT id,
			lead_id,
			stage_id,
			stage,
			stage_wise_msg,
			create_datetime FROM lead_stage_log WHERE lead_id='".$lid."' ORDER BY id DESC LIMIT 1,1";
		$result=$this->client_db->query($sql);
		//return $result->row();

		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	public function get_prev_status($lid,$client_info=array())
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

		$sql="SELECT id,lead_id,status_id,status,create_datetime FROM lead_status_log WHERE lead_id='".$lid."' ORDER BY id DESC LIMIT 1,1";
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	public function prev_next_id($id,$pos)
	{
		$sql="";

		$search_data=$this->session->userdata('lead_search');
		$subsql='';
		if($search_data['assigned_user']!='')
		{
			$subsql.=" AND assigned_user_id IN (".$search_data['assigned_user'].")";
		}

		if($search_data['opportunity_stage']!='')
		{
			$subsql.=" AND current_stage_id=".$search_data['opportunity_stage'];
		}
		
		if($search_data['opportunity_status']!='')
		{
			if($search_data['opportunity_status']=='Y')
			{
				$subsql.=" AND is_hotstar='Y'";
			}
			else
			{
				$subsql.=" AND current_status_id=".$search_data['opportunity_status'];
			}
			
		}
		
		if($search_data['lead_to_date']!='' && $search_data['lead_from_date']!='')
		{
			$from = date_display_format_to_db_format($search_data['lead_from_date']);  
			$to = date_display_format_to_db_format($search_data['lead_to_date']);  
			$subsql.=" AND (enquiry_date BETWEEN '".$from."' AND '".$to."') ";
		}

		if($search_data['search_keyword']!='')
		{
			$subsql.=" AND title LIKE '%".$search_data['search_keyword']."%'";
		}

    	if($pos=='next'){
    		$sql="SELECT id FROM lead WHERE id = (SELECT min(id) FROM lead WHERE id > ".$id." $subsql) $subsql";
    	}else{
    		$sql="SELECT id FROM lead WHERE id = (SELECT max(id) FROM lead WHERE id < ".$id." $subsql) $subsql";
    	}
    	$result=$this->client_db->query($sql);
    	if($result->num_rows()){
			return $result->row()->id;
		}else{
			return 0;
		}
    }
	
	// AJAX SEARCH START
	function get_type_wise_lead_stage($type)
	{
		if($type=='AL')
		{			
			$sql="SELECT id FROM opportunity_stage 
			WHERE id NOT IN ('3','4','5','6','7')";
			$result=$this->client_db->query($sql);
			if($result){
				if($result->num_rows())
				{
					$tmp_return='';
					$rows=$result->result();
					foreach($rows AS $row)
					{
						$tmp_return .="'".$row->id."',";
					}
					return rtrim($tmp_return, ',');
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
		else if($type=='LL')
		{
			return "'3','5','6','7'";

		}
		else if($type=='WL')
		{
			return "'4'";	
		}
		else if($type=='NAL') // Not active lead
		{
			return "'4','3','5','6','7'";	
		}
		else
		{
			$sql="SELECT id FROM opportunity_stage";
			$result=$this->client_db->query($sql);
			if($result){
				if($result->num_rows())
				{
					$tmp_return='';
					$rows=$result->result();
					foreach($rows AS $row)
					{
						$tmp_return .="'".$row->id."',";
					}
					return rtrim($tmp_return, ',');
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

	function get_csv_list_count($argument=NULL)
    {
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
		$subsqlHaving='';
        

        // ---------------------------------------
        // SEARCH VALUE 
		if($argument['filter_common_lead_pool']=='Y')
		{
			if($argument['filter_lead_type_wise_stages']){
				//$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
			}
			$subsql.=" AND lead.assigned_user_id IN ('0')";			
		}	
		else if($argument['filter_lead_observer']=='Y')
		{
			if($argument['filter_lead_type_wise_stages']){
				//$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
			}
			if($argument['assigned_observer']){
				$subsql.=" AND lead.assigned_observer IN ('".$argument['assigned_observer']."')";
			}	
		}	
		else if($argument['filter_search_by_id']!='')
		{
			$subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
		}
		else
		{
				
			if($argument['assigned_user']!='')
			{
				$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
			}
			if(strtoupper($argument['filter_followup'])=='AL' || strtoupper($argument['filter_followup'])=='NL' || strtoupper($argument['filter_followup'])=='TL' || strtoupper($argument['filter_followup'])=='PL' || strtoupper($argument['filter_followup'])=='UL')
			{
				$subsql.=" AND lead.assigned_user_id NOT IN ('0')";				
				if($argument['filter_lead_type_wise_stages']){
					$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
				}
				
				$today_date=date("Y-m-d");
				if(strtoupper($argument['filter_followup'])=='NL')
				{
					$subsql.=" AND lead.is_followup_date_changed='N'";
				}
				else if(strtoupper($argument['filter_followup'])=='TL')
				{
					$order_by_str = " ORDER BY lead.followup_date ASC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)='".$today_date."')";
				}
				else if(strtoupper($argument['filter_followup'])=='PL')
				{		
					$order_by_str = " ORDER BY lead.followup_date DESC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)<'".$today_date."')";
							
				}
				else if(strtoupper($argument['filter_followup'])=='UL')
				{		
					$order_by_str = " ORDER BY lead.followup_date ASC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)>'".$today_date."')";
							
				}
				else
				{
					
					
				}
				
			}
			else
			{	
				
				/*
				if($argument['filter_search_by_id']!='')
				{
					$subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
				}*/
				//print_r($argument); die();
				if($argument['filter_search_str']!='')
				{

					$subsql .= " AND  (lead.id = '".$argument['filter_search_str']."' || lead.title LIKE '%".$argument['filter_search_str']."%' ||  lead.buying_requirement LIKE '%".$argument['filter_search_str']."%' || cus.contact_person LIKE '%".$argument['filter_search_str']."%' || cus.email LIKE '%".$argument['filter_search_str']."%' || cus.mobile LIKE '%".$argument['filter_search_str']."%' || cus.company_name LIKE '%".$argument['filter_search_str']."%' || cities.name LIKE '%".$argument['filter_search_str']."%' || states.name LIKE '%".$argument['filter_search_str']."%' || countries.name LIKE '%".$argument['filter_search_str']."%')";
				}
				
				if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
				{
					$date_filter_by=$argument['date_filter_by'];
					$from = date_display_format_to_db_format($argument['lead_from_date']);  
					$to = date_display_format_to_db_format($argument['lead_to_date']);  
					
					if($date_filter_by=='added_on')
					{
						$date_filter_by_str="lead.enquiry_date";
						$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
						
					}
					else if($date_filter_by=='updated_on')
					{
						$date_filter_by_str="lead.modify_date";
						$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
						$subsqlInner .=" INNER JOIN lead_comment ON lead_comment.lead_id=lead.id";
						$subsql .=" AND (replace(LOWER(lead_comment.title) , ' ','') NOT LIKE '%newleadcreated%') AND (DATE(lead_comment.create_date) BETWEEN '".$from."' AND '".$to."')";
					}
					else if($date_filter_by=='follow_up_on')
					{				
						$date_filter_by_str="DATE(lead.followup_date)";
						$subsql.=" AND lead.current_stage_id IN ('1','2')";
					}
					else if($date_filter_by=='quoted_on' || $date_filter_by=='regretted_on' || $date_filter_by=='deal_losted_on' || $date_filter_by=='deal_won_on')
					{
						if($date_filter_by=='quoted_on'){
							$stageid='2';
						}
						else if($date_filter_by=='regretted_on'){
							$stageid='3,6';
						}
						else if($date_filter_by=='deal_losted_on'){
							$stageid='5,7';
						}
						else if($date_filter_by=='deal_won_on'){
							$stageid=4;				
						}				
						$date_filter_by_str="DATE(stage_log2.create_datetime)";
						$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log2 ON stage_log2.lead_id=lead.id AND stage_log2.stage_id IN (".$stageid.")";
					}
					
					//$from = date_display_format_to_db_format($argument['lead_from_date']);  
					//$to = date_display_format_to_db_format($argument['lead_to_date']);  
					//$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
				}
				
				
				

				
				
				if($argument['lead_applicable_for']!='')
				{		
					$is_export_filter='';
					$is_domestic_filter='';
					if($argument['lead_applicable_for']=='E')
					{
						$is_export_filter='Y';
					}
					else if($argument['lead_applicable_for']=='D')
					{				
						$is_domestic_filter='Y';
					}
					else
					{
						$lead_applicable_for_arr=explode(",",$argument['lead_applicable_for']);
						$is_export_filter='Y';
						$is_domestic_filter='Y';
					}
					
					$company=get_company_profile();
					$company_country_id=$company['country_id'];				
					if($is_export_filter=='Y' && $is_domestic_filter=='')
					{
						$subsql.=" AND cus.country_id !='".$company_country_id."'";
					}
					
					if($is_export_filter=='' && $is_domestic_filter=='Y')
					{
						$subsql.=" AND cus.country_id ='".$company_country_id."'";
					}
				}
				

				
				/*
				if($argument['opportunity_stage']!='')
				{
					$subsql_tmp='';
					if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
					{						
						$from = date_display_format_to_db_format($argument['lead_from_date']);  
						$to = date_display_format_to_db_format($argument['lead_to_date']);
						$subsql_tmp=" AND (DATE(stage_log_inner.create_datetime) BETWEEN '".$from."' AND '".$to."')";
					}
					$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN (".$argument['opportunity_stage'].") $subsql_tmp";
				}*/
				
				
				if($argument['opportunity_status']!='')
				{
					//$subsql.=" AND lead.current_status_id IN (".$argument['opportunity_status'].")";
					$subsql_tmp='';
					if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
					{						
						$from = date_display_format_to_db_format($argument['lead_from_date']);  
						$to = date_display_format_to_db_format($argument['lead_to_date']);
						$subsql_tmp=" AND (DATE(status_log_inner.create_datetime) BETWEEN '".$from."' AND '".$to."')";
					}
					$subsqlInner .=" INNER JOIN lead_status_log AS status_log_inner ON status_log_inner.lead_id=lead.id AND status_log_inner.status_id IN (".$argument['opportunity_status'].") $subsql_tmp";
					if($argument['is_hotstar']=='Y')
					{
						$subsql.=" OR lead.is_hotstar='Y'";
					}
				}
				else
				{
					if($argument['is_hotstar']!='')
					{
						if($argument['is_hotstar']=='Y')
						{
							$subsql.=" AND lead.is_hotstar='Y'";
						}			
					}
				}	
				
				if($argument['pending_followup']!='')
				{			
					$curr_date = date('Y-m-d');
					$current_time = strtotime($curr_date);
					$yesterday_date = date('Y-m-d', strtotime('-1 day', $current_time));
					$two_day_before = date('Y-m-d', strtotime('-2 day', $current_time));
					$five_day_before = date('Y-m-d', strtotime('-5 day', $current_time));

					if($argument['pending_followup_for']=='')
					{

						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$curr_date."')";

					}
					else if($argument['pending_followup_for']=='today')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)='".$curr_date."')";
					}
					else if($argument['pending_followup_for']=='yesterday')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)='".$yesterday_date."')";
					}
					else if($argument['pending_followup_for']=='twodaysbefore')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$two_day_before."')";
					}
					else if($argument['pending_followup_for']=='fivedaysbefore')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$five_day_before."')";

					}			
				}


				if($argument['source_ids']!='')
				{			
					$subsql.=" AND lead.source_id IN (".$argument['source_ids'].")";
				}

				if($argument['filter_like_dsc']!='')
				{			
					$current_stage_ids='';
					$assigned_user_ids='';
					$uri_str=base64_decode($argument['filter_like_dsc']);
					$uri_str_arr=explode("#", $uri_str);
					$assigned_user_ids=$uri_str_arr[0];
					$filter_by=$uri_str_arr[1];		
					
					//$subsql.=" AND lead.assigned_user_id IN (".$assigned_user_ids.")";
					
					if($filter_by=='quoted_leads'){
						//$subsql.=" AND lead.current_stage_id IN (2)";
						if($argument['filter_lead_type_wise_stages']!='')
						{		
							$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";				
						}
						$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN ('2')";
					}
					
				}
				else
				{					
					if($argument['opportunity_stage_filter_type']=='all_stage')
					{
						if($argument['filter_lead_type']=='AL' && $argument['opportunity_stage']=='1')
						{
							$subsql.=" AND lead.current_stage_id IN (".$argument['opportunity_stage'].")";
						}
						else
						{
							if($argument['filter_lead_type']!='' && $argument['filter_lead_type_wise_stages']!='')
							{		
								$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";
							}
							if($argument['opportunity_stage']!='')
							{
								$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN (".$argument['opportunity_stage'].")";
							}							
						}
					}
					else
					{						
						$subsql.=" AND ll_stage.stage_id IN (".$argument['opportunity_stage'].")";
						
					}
				}
								
			}
		}	


		$subsqlInner.=" LEFT JOIN 
							(SELECT t1.*
								FROM 
								(
									SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
									FROM 
									(
										SELECT t1.id,t1.lead_id,t1.stage_id,t2.name,t2.sort,t3.total_stage
										FROM `lead_stage_log` as t1 
										JOIN (SELECT * FROM `opportunity_stage` WHERE id NOT IN (3,4,5,6,7) AND `is_active_lead`='Y' AND `is_deleted` = 'N' ORDER BY sort) AS t2 ON t1.stage_id = t2.id
																	
										JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1 
										JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id 
										ORDER BY `lead_id`,t2.sort,t2.id
									) AS t
								) AS t1				
								WHERE last_stage_id IS NOT null	GROUP BY lead_id
							) AS ll_stage ON lead.id=ll_stage.lead_id";
		
		
        // SEARCH VALUE
        // ---------------------------------------
		//echo $subsql; die();
		$sql="SELECT 			
			lead.id 
			FROM lead 
			LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id
			LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			LEFT JOIN source ON source.id=lead.source_id 
			LEFT JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN user AS observer ON observer.id=lead.assigned_observer 
			LEFT JOIN lead_opportunity ON lead_opportunity.lead_id=lead.id 
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			LEFT JOIN indiamart_setting AS im_setting ON lead.im_setting_id=im_setting.id 
			LEFT JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id 
			LEFT JOIN lead_wise_product_service AS tagged_ps ON tagged_ps.lead_id=lead.id $subsqlInner WHERE lead.status='1' $subsql GROUP BY lead.id $subsqlHaving";

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
	
	function get_csv_list($argument=NULL)
    {       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
		$subsqlHaving='';
       
        $order_by_str = " ORDER BY lead.id DESC ";
        if($argument['filter_sort_by']!='')
        {			
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 
		if($argument['filter_common_lead_pool']=='Y')
		{
			if($argument['filter_lead_type_wise_stages']){
				//$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
			}
			$subsql.=" AND lead.assigned_user_id IN ('0')";			
		}	
		else if($argument['filter_lead_observer']=='Y')
		{
			if($argument['filter_lead_type_wise_stages']){
				//$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
			}
			if($argument['assigned_observer']){
				$subsql.=" AND lead.assigned_observer IN ('".$argument['assigned_observer']."')";
			}	
		}	
		else if($argument['filter_search_by_id']!='')
		{
			$subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
		}
		else
		{
				
			if($argument['assigned_user']!='')
			{
				$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
			}
			if(strtoupper($argument['filter_followup'])=='AL' || strtoupper($argument['filter_followup'])=='NL' || strtoupper($argument['filter_followup'])=='TL' || strtoupper($argument['filter_followup'])=='PL' || strtoupper($argument['filter_followup'])=='UL')
			{
				$subsql.=" AND lead.assigned_user_id NOT IN ('0')";				
				if($argument['filter_lead_type_wise_stages']){
					$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
				}
				
				$today_date=date("Y-m-d");
				if(strtoupper($argument['filter_followup'])=='NL')
				{
					$subsql.=" AND lead.is_followup_date_changed='N'";
				}
				else if(strtoupper($argument['filter_followup'])=='TL')
				{
					$order_by_str = " ORDER BY lead.followup_date ASC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)='".$today_date."')";
				}
				else if(strtoupper($argument['filter_followup'])=='PL')
				{		
					$order_by_str = " ORDER BY lead.followup_date DESC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)<'".$today_date."')";
							
				}
				else if(strtoupper($argument['filter_followup'])=='UL')
				{		
					$order_by_str = " ORDER BY lead.followup_date ASC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)>'".$today_date."')";
							
				}
				else
				{
					
					
				}
				
			}
			else
			{	
				
				/*
				if($argument['filter_search_by_id']!='')
				{
					$subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
				}*/
				//print_r($argument); die();
				if($argument['filter_search_str']!='')
				{

					$subsql .= " AND  (lead.id = '".$argument['filter_search_str']."' || lead.title LIKE '%".$argument['filter_search_str']."%' ||  lead.buying_requirement LIKE '%".$argument['filter_search_str']."%' || cus.contact_person LIKE '%".$argument['filter_search_str']."%' || cus.email LIKE '%".$argument['filter_search_str']."%' || cus.mobile LIKE '%".$argument['filter_search_str']."%' || cus.company_name LIKE '%".$argument['filter_search_str']."%' || cities.name LIKE '%".$argument['filter_search_str']."%' || states.name LIKE '%".$argument['filter_search_str']."%' || countries.name LIKE '%".$argument['filter_search_str']."%')";
				}
				
				if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
				{
					$date_filter_by=$argument['date_filter_by'];
					$from = date_display_format_to_db_format($argument['lead_from_date']);  
					$to = date_display_format_to_db_format($argument['lead_to_date']);  
					
					if($date_filter_by=='added_on')
					{
						$date_filter_by_str="lead.enquiry_date";
						$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
						
					}
					else if($date_filter_by=='updated_on')
					{
						$date_filter_by_str="lead.modify_date";
						$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
						$subsqlInner .=" INNER JOIN lead_comment ON lead_comment.lead_id=lead.id";
						$subsql .=" AND (replace(LOWER(lead_comment.title) , ' ','') NOT LIKE '%newleadcreated%') AND (DATE(lead_comment.create_date) BETWEEN '".$from."' AND '".$to."')";
					}
					else if($date_filter_by=='follow_up_on')
					{				
						$date_filter_by_str="DATE(lead.followup_date)";
						$subsql.=" AND lead.current_stage_id IN ('1','2')";
					}
					else if($date_filter_by=='quoted_on' || $date_filter_by=='regretted_on' || $date_filter_by=='deal_losted_on' || $date_filter_by=='deal_won_on')
					{
						if($date_filter_by=='quoted_on'){
							$stageid='2';
						}
						else if($date_filter_by=='regretted_on'){
							$stageid='3,6';
						}
						else if($date_filter_by=='deal_losted_on'){
							$stageid='5,7';
						}
						else if($date_filter_by=='deal_won_on'){
							$stageid=4;				
						}				
						$date_filter_by_str="DATE(stage_log2.create_datetime)";
						$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log2 ON stage_log2.lead_id=lead.id AND stage_log2.stage_id IN (".$stageid.")";
					}
					
					//$from = date_display_format_to_db_format($argument['lead_from_date']);  
					//$to = date_display_format_to_db_format($argument['lead_to_date']);  
					//$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
				}
				
				
				

				
				
				if($argument['lead_applicable_for']!='')
				{		
					$is_export_filter='';
					$is_domestic_filter='';
					if($argument['lead_applicable_for']=='E')
					{
						$is_export_filter='Y';
					}
					else if($argument['lead_applicable_for']=='D')
					{				
						$is_domestic_filter='Y';
					}
					else
					{
						$lead_applicable_for_arr=explode(",",$argument['lead_applicable_for']);
						$is_export_filter='Y';
						$is_domestic_filter='Y';
					}
					
					$company=get_company_profile();
					$company_country_id=$company['country_id'];				
					if($is_export_filter=='Y' && $is_domestic_filter=='')
					{
						$subsql.=" AND cus.country_id !='".$company_country_id."'";
					}
					
					if($is_export_filter=='' && $is_domestic_filter=='Y')
					{
						$subsql.=" AND cus.country_id ='".$company_country_id."'";
					}
				}
				

				
				/*
				if($argument['opportunity_stage']!='')
				{
					$subsql_tmp='';
					if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
					{						
						$from = date_display_format_to_db_format($argument['lead_from_date']);  
						$to = date_display_format_to_db_format($argument['lead_to_date']);
						$subsql_tmp=" AND (DATE(stage_log_inner.create_datetime) BETWEEN '".$from."' AND '".$to."')";
					}
					$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN (".$argument['opportunity_stage'].") $subsql_tmp";
				}*/
				
				
				if($argument['opportunity_status']!='')
				{
					//$subsql.=" AND lead.current_status_id IN (".$argument['opportunity_status'].")";
					$subsql_tmp='';
					if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
					{						
						$from = date_display_format_to_db_format($argument['lead_from_date']);  
						$to = date_display_format_to_db_format($argument['lead_to_date']);
						$subsql_tmp=" AND (DATE(status_log_inner.create_datetime) BETWEEN '".$from."' AND '".$to."')";
					}
					$subsqlInner .=" INNER JOIN lead_status_log AS status_log_inner ON status_log_inner.lead_id=lead.id AND status_log_inner.status_id IN (".$argument['opportunity_status'].") $subsql_tmp";
					if($argument['is_hotstar']=='Y')
					{
						$subsql.=" OR lead.is_hotstar='Y'";
					}
				}
				else
				{
					if($argument['is_hotstar']!='')
					{
						if($argument['is_hotstar']=='Y')
						{
							$subsql.=" AND lead.is_hotstar='Y'";
						}			
					}
				}	
				
				if($argument['pending_followup']!='')
				{			
					$curr_date = date('Y-m-d');
					$current_time = strtotime($curr_date);
					$yesterday_date = date('Y-m-d', strtotime('-1 day', $current_time));
					$two_day_before = date('Y-m-d', strtotime('-2 day', $current_time));
					$five_day_before = date('Y-m-d', strtotime('-5 day', $current_time));

					if($argument['pending_followup_for']=='')
					{

						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$curr_date."')";

					}
					else if($argument['pending_followup_for']=='today')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)='".$curr_date."')";
					}
					else if($argument['pending_followup_for']=='yesterday')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)='".$yesterday_date."')";
					}
					else if($argument['pending_followup_for']=='twodaysbefore')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$two_day_before."')";
					}
					else if($argument['pending_followup_for']=='fivedaysbefore')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$five_day_before."')";

					}			
				}


				if($argument['source_ids']!='')
				{			
					$subsql.=" AND lead.source_id IN (".$argument['source_ids'].")";
				}

				if($argument['filter_like_dsc']!='')
				{			
					$current_stage_ids='';
					$assigned_user_ids='';
					$uri_str=base64_decode($argument['filter_like_dsc']);
					$uri_str_arr=explode("#", $uri_str);
					$assigned_user_ids=$uri_str_arr[0];
					$filter_by=$uri_str_arr[1];		
					
					//$subsql.=" AND lead.assigned_user_id IN (".$assigned_user_ids.")";
					
					if($filter_by=='quoted_leads'){
						//$subsql.=" AND lead.current_stage_id IN (2)";
						if($argument['filter_lead_type_wise_stages']!='')
						{		
							$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";				
						}
						$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN ('2')";
					}
					
				}
				else
				{					
					if($argument['opportunity_stage_filter_type']=='all_stage')
					{
						if($argument['filter_lead_type']=='AL' && $argument['opportunity_stage']=='1')
						{
							$subsql.=" AND lead.current_stage_id IN (".$argument['opportunity_stage'].")";
						}
						else
						{
							if($argument['filter_lead_type']!='' && $argument['filter_lead_type_wise_stages']!='')
							{		
								$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";
							}
							if($argument['opportunity_stage']!='')
							{
								$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN (".$argument['opportunity_stage'].")";
							}							
						}
					}
					else
					{						
						$subsql.=" AND ll_stage.stage_id IN (".$argument['opportunity_stage'].")";
						
					}
				}
								
			}
		}	


		$subsqlInner.=" LEFT JOIN 
							(SELECT t1.*
								FROM 
								(
									SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
									FROM 
									(
										SELECT t1.id,t1.lead_id,t1.stage_id,t2.name,t2.sort,t3.total_stage
										FROM `lead_stage_log` as t1 
										JOIN (SELECT * FROM `opportunity_stage` WHERE id NOT IN (3,4,5,6,7) AND `is_active_lead`='Y' AND `is_deleted` = 'N' ORDER BY sort) AS t2 ON t1.stage_id = t2.id
																	
										JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1 
										JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id 
										ORDER BY `lead_id`,t2.sort,t2.id
									) AS t
								) AS t1				
								WHERE last_stage_id IS NOT null	GROUP BY lead_id
							) AS ll_stage ON lead.id=ll_stage.lead_id";
		
		
        // SEARCH VALUE
        // ---------------------------------------	
		$sql="SELECT 			
			lo.*,
			lo2.*,
			lo3.*,
			lead.id,
			lead.title,
			lead.customer_id,
			lead.assigned_user_id,
			lead.buying_requirement,
			lead.enquiry_date,
			lead.followup_date,
			lead.modify_date,
			lead.current_stage_id,			
			lead.current_stage,
			lead.current_stage_wise_msg,
			lead.current_status,
			lead.is_hotstar,
			lead.closer_date,
			lead.deal_value,
			lead.deal_value_currency_code,
			assigned_user_table.*,
			observer.name AS assigned_observer_name,
			c_paying_info.*,
			c_r_c.*,
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
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,
			source.name AS source_name,
			im_setting.account_name AS im_account_name,
			GROUP_CONCAT(stage_log.stage_id ORDER BY stage_log.id) AS stage_logs,
			GROUP_CONCAT(DISTINCT CONCAT(tagged_ps.id,'#',tagged_ps.name)) AS tagged_ps,lc.*		
			FROM lead 
			LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id
			LEFT JOIN (SELECT lead.customer_id AS custid,GROUP_CONCAT( s_log.stage_id SEPARATOR ',' ) As orders,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )),'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			LEFT JOIN source ON source.id=lead.source_id 
			LEFT JOIN 
			(
				SELECT 
				user.id AS uid,
				user.name AS assigned_user_name,
				employee_type.name AS assigned_user_employee_type,
				company_branch.name AS assigned_user_branch_name,
				if(company_branch.company_setting_id,'Main Branch','') AS assigned_user_branch_name_cs
				FROM user 
				LEFT JOIN employee_type ON employee_type.id=user.employee_type_id 
				LEFT JOIN company_branch ON company_branch.id=user.branch_id
			) AS assigned_user_table ON assigned_user_table.uid=lead.assigned_user_id 			
			LEFT JOIN user AS observer ON observer.id=lead.assigned_observer 
			LEFT JOIN lead_opportunity ON lead_opportunity.lead_id=lead.id 	
			LEFT JOIN (
				SELECT 
				COUNT(id) AS proposal,
				lead_id AS lo_lid,
				SUM(deal_value) AS total_deal_value,
				SUM(deal_value_as_per_purchase_order) AS total_deal_value_as_per_purchase_order
				FROM lead_opportunity GROUP BY lead_id
			) AS lo ON lo.lo_lid=lead.id
			LEFT JOIN (
				SELECT lead_id AS lo_lid2,
				SUBSTRING_INDEX(GROUP_CONCAT(deal_value),',', -1) AS quotation_sent_deal_value,
				currency.code AS quotation_sent_currency_code
				FROM lead_opportunity 
				INNER JOIN currency ON lead_opportunity.currency_type=currency.id 
				WHERE status='2' GROUP BY lead_id
			) AS lo2 ON lo2.lo_lid2=lead.id
			LEFT JOIN (
				SELECT lead_id AS lo_lid3,
				SUBSTRING_INDEX(GROUP_CONCAT(deal_value),',', -1) AS quotation_matured_deal_value,
				SUBSTRING_INDEX(GROUP_CONCAT(deal_value_as_per_purchase_order),',', -1) AS quotation_matured_deal_value_as_per_purchase_order,
				currency.code AS quotation_matured_currency_code
				FROM lead_opportunity 
				INNER JOIN currency ON lead_opportunity.currency_type=currency.id  
				WHERE status='4' GROUP BY lead_id
			) AS lo3 ON lo3.lo_lid3=lead.id

			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			LEFT JOIN indiamart_setting AS im_setting ON lead.im_setting_id=im_setting.id 
			LEFT JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id 
			LEFT JOIN lead_wise_product_service AS tagged_ps ON tagged_ps.lead_id=lead.id 
			LEFT JOIN 			
			( 
				SELECT p1.lead_id AS lid,p1.comment AS lastComment 
				FROM lead_comment p1 
				INNER JOIN (SELECT pi.lead_id AS lid, MAX(pi.id) AS maxpostid FROM lead_comment pi 
				GROUP BY pi.lead_id) p2 ON (p1.id = p2.maxpostid)
			) AS lc ON lc.lid=lead.id $subsqlInner WHERE lead.status='1' $subsql GROUP BY lead.id $subsqlHaving $order_by_str LIMIT $start,$limit";
		//  echo $sql;die();
        $query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();die();
        //return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
    }
	
	
    function get_list_count($argument=NULL)
    {
        $subsql = ''; 
		$subsqlInner='';
		$subsqlHaving='';
        // ---------------------------------------
        // SEARCH VALUE 
		if($argument['filter_common_lead_pool']=='Y')
		{
			if($argument['filter_lead_type_wise_stages']){
				//$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
			}
			$subsql.=" AND lead.assigned_user_id IN ('0')";			
		}	
		else if($argument['filter_lead_observer']=='Y')
		{
			if($argument['filter_lead_type_wise_stages']){
				//$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
			}
			if($argument['assigned_observer']){
				$subsql.=" AND lead.assigned_observer IN ('".$argument['assigned_observer']."')";
			}	
		}	
		else if($argument['filter_search_by_id']!='')
		{
			$subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
		}
		else
		{
				
			if($argument['assigned_user']!='')
			{
				$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
			}
			if(strtoupper($argument['filter_followup'])=='AL' || strtoupper($argument['filter_followup'])=='NL' || strtoupper($argument['filter_followup'])=='TL' || strtoupper($argument['filter_followup'])=='PL' || strtoupper($argument['filter_followup'])=='UL')
			{
				$subsql.=" AND lead.assigned_user_id NOT IN ('0')";				
				if($argument['filter_lead_type_wise_stages']){
					$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
				}
				
				$today_date=date("Y-m-d");
				if(strtoupper($argument['filter_followup'])=='NL')
				{
					$subsql.=" AND lead.is_followup_date_changed='N'";
				}
				else if(strtoupper($argument['filter_followup'])=='TL')
				{
					$order_by_str = " ORDER BY lead.followup_date ASC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)='".$today_date."')";
				}
				else if(strtoupper($argument['filter_followup'])=='PL')
				{		
					$order_by_str = " ORDER BY lead.followup_date DESC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)<'".$today_date."')";
							
				}
				else if(strtoupper($argument['filter_followup'])=='UL')
				{		
					$order_by_str = " ORDER BY lead.followup_date ASC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)>'".$today_date."')";
							
				}
				else
				{
					
					
				}
				
			}
			else
			{	
				
				/*
				if($argument['filter_search_by_id']!='')
				{
					$subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
				}*/
				//print_r($argument); die();
				if($argument['filter_search_str']!='')
				{

					$subsql .= " AND  (lead.id = '".$argument['filter_search_str']."' || lead.title LIKE '%".$argument['filter_search_str']."%' ||  lead.buying_requirement LIKE '%".$argument['filter_search_str']."%' || cus.contact_person LIKE '%".$argument['filter_search_str']."%' || cus.email LIKE '%".$argument['filter_search_str']."%' || cus.mobile LIKE '%".$argument['filter_search_str']."%' || cus.company_name LIKE '%".$argument['filter_search_str']."%' || cities.name LIKE '%".$argument['filter_search_str']."%' || states.name LIKE '%".$argument['filter_search_str']."%' || countries.name LIKE '%".$argument['filter_search_str']."%')";
				}
				
				if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
				{
					$date_filter_by=$argument['date_filter_by'];
					$from = date_display_format_to_db_format($argument['lead_from_date']);  
					$to = date_display_format_to_db_format($argument['lead_to_date']);  
					
					if($date_filter_by=='added_on')
					{
						$date_filter_by_str="lead.enquiry_date";
						$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
						
					}
					else if($date_filter_by=='follow_up_date')
					{
						$date_filter_by_str="lead.followup_date";
						$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
					}
					else if($date_filter_by=='updated_on')
					{
						$date_filter_by_str="lead.modify_date";
						$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
						$subsqlInner .=" INNER JOIN lead_comment ON lead_comment.lead_id=lead.id";
						$subsql .=" AND (replace(LOWER(lead_comment.title) , ' ','') NOT LIKE '%newleadcreated%') AND (DATE(lead_comment.create_date) BETWEEN '".$from."' AND '".$to."')";
					}
					else if($date_filter_by=='follow_up_on')
					{				
						$date_filter_by_str="DATE(lead.followup_date)";
						$subsql.=" AND lead.current_stage_id IN ('1','2')";
					}
					else if($date_filter_by=='quoted_on' || $date_filter_by=='regretted_on' || $date_filter_by=='deal_losted_on' || $date_filter_by=='deal_won_on')
					{
						if($date_filter_by=='quoted_on'){
							$stageid='2';
						}
						else if($date_filter_by=='regretted_on'){
							$stageid='3,6';
						}
						else if($date_filter_by=='deal_losted_on'){
							$stageid='5,7';
						}
						else if($date_filter_by=='deal_won_on'){
							$stageid=4;				
						}				
						$date_filter_by_str="DATE(stage_log2.create_datetime)";
						$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log2 ON stage_log2.lead_id=lead.id AND stage_log2.stage_id IN (".$stageid.")";
					}
					
					//$from = date_display_format_to_db_format($argument['lead_from_date']);  
					//$to = date_display_format_to_db_format($argument['lead_to_date']);  
					//$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
				}
				
				
				

				
				
				if($argument['lead_applicable_for']!='')
				{		
					$is_export_filter='';
					$is_domestic_filter='';
					if($argument['lead_applicable_for']=='E')
					{
						$is_export_filter='Y';
					}
					else if($argument['lead_applicable_for']=='D')
					{				
						$is_domestic_filter='Y';
					}
					else
					{
						$lead_applicable_for_arr=explode(",",$argument['lead_applicable_for']);
						$is_export_filter='Y';
						$is_domestic_filter='Y';
					}
					
					$company=get_company_profile();
					$company_country_id=$company['country_id'];				
					if($is_export_filter=='Y' && $is_domestic_filter=='')
					{
						$subsql.=" AND cus.country_id !='".$company_country_id."'";
					}
					
					if($is_export_filter=='' && $is_domestic_filter=='Y')
					{
						$subsql.=" AND cus.country_id ='".$company_country_id."'";
					}
				}
				

				
				/*
				if($argument['opportunity_stage']!='')
				{
					$subsql_tmp='';
					if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
					{						
						$from = date_display_format_to_db_format($argument['lead_from_date']);  
						$to = date_display_format_to_db_format($argument['lead_to_date']);
						$subsql_tmp=" AND (DATE(stage_log_inner.create_datetime) BETWEEN '".$from."' AND '".$to."')";
					}
					$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN (".$argument['opportunity_stage'].") $subsql_tmp";
				}*/
				
				
				if($argument['opportunity_status']!='')
				{
					//$subsql.=" AND lead.current_status_id IN (".$argument['opportunity_status'].")";
					$subsql_tmp='';
					if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
					{						
						$from = date_display_format_to_db_format($argument['lead_from_date']);  
						$to = date_display_format_to_db_format($argument['lead_to_date']);
						$subsql_tmp=" AND (DATE(status_log_inner.create_datetime) BETWEEN '".$from."' AND '".$to."')";
					}
					$subsqlInner .=" INNER JOIN lead_status_log AS status_log_inner ON status_log_inner.lead_id=lead.id AND status_log_inner.status_id IN (".$argument['opportunity_status'].") $subsql_tmp";
					if($argument['is_hotstar']=='Y')
					{
						$subsql.=" OR lead.is_hotstar='Y'";
					}
				}
				else
				{
					if($argument['is_hotstar']!='')
					{
						if($argument['is_hotstar']=='Y')
						{
							$subsql.=" AND lead.is_hotstar='Y'";
						}			
					}
				}	
				
				if($argument['pending_followup']!='')
				{			
					$curr_date = date('Y-m-d');
					$current_time = strtotime($curr_date);
					$yesterday_date = date('Y-m-d', strtotime('-1 day', $current_time));
					$two_day_before = date('Y-m-d', strtotime('-2 day', $current_time));
					$five_day_before = date('Y-m-d', strtotime('-5 day', $current_time));

					if($argument['pending_followup_for']=='')
					{

						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$curr_date."')";

					}
					else if($argument['pending_followup_for']=='today')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)='".$curr_date."')";
					}
					else if($argument['pending_followup_for']=='yesterday')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)='".$yesterday_date."')";
					}
					else if($argument['pending_followup_for']=='twodaysbefore')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$two_day_before."')";
					}
					else if($argument['pending_followup_for']=='fivedaysbefore')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$five_day_before."')";

					}			
				}


				if($argument['source_ids']!='')
				{			
					$subsql.=" AND lead.source_id IN (".$argument['source_ids'].")";
				}

				if($argument['filter_like_dsc']!='')
				{			
					$current_stage_ids='';
					$assigned_user_ids='';
					$uri_str=base64_decode($argument['filter_like_dsc']);
					$uri_str_arr=explode("#", $uri_str);
					$assigned_user_ids=$uri_str_arr[0];
					$filter_by=$uri_str_arr[1];		
					
					//$subsql.=" AND lead.assigned_user_id IN (".$assigned_user_ids.")";
					
					if($filter_by=='quoted_leads'){
						//$subsql.=" AND lead.current_stage_id IN (2)";
						if($argument['filter_lead_type_wise_stages']!='')
						{		
							$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";				
						}
						$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN ('2')";
					}
					
				}
				else
				{					
					if($argument['opportunity_stage_filter_type']=='all_stage')
					{
						if($argument['filter_lead_type']=='AL' && $argument['opportunity_stage']=='1')
						{
							$subsql.=" AND lead.current_stage_id IN (".$argument['opportunity_stage'].")";
						}
						else
						{
							if($argument['filter_lead_type']!='' && $argument['filter_lead_type_wise_stages']!='')
							{		
								$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";
							}
							if($argument['opportunity_stage']!='')
							{
								$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN (".$argument['opportunity_stage'].")";
							}							
						}
					}
					else
					{						
						$subsql.=" AND ll_stage.stage_id IN (".$argument['opportunity_stage'].")";
						
					}
				}
								
			}
		}	


		$subsqlInner.=" LEFT JOIN 
							(SELECT t1.*
								FROM 
								(
									SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
									FROM 
									(
										SELECT t1.id,t1.lead_id,t1.stage_id,t2.name,t2.sort,t3.total_stage
										FROM `lead_stage_log` as t1 
										JOIN (SELECT * FROM `opportunity_stage` WHERE id NOT IN (3,4,5,6,7) AND `is_active_lead`='Y' AND `is_deleted` = 'N' ORDER BY sort) AS t2 ON t1.stage_id = t2.id
																	
										JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1 
										JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id 
										ORDER BY `lead_id`,t2.sort,t2.id
									) AS t
								) AS t1				
								WHERE last_stage_id IS NOT null	GROUP BY lead_id
							) AS ll_stage ON lead.id=ll_stage.lead_id";
		
		// $subsqlInner.=" LEFT JOIN 
		// 	(SELECT t1.*
		// 		FROM 
		// 		(
		// 			SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
		// 			FROM 
		// 			(
		// 				SELECT t1.id,t1.lead_id,t1.stage_id,t2.name,t2.sort,t3.total_stage
		// 				FROM `lead_stage_log` as t1 
		// 				JOIN opportunity_stage AS t2 ON t1.stage_id=t2.id							
		// 				JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1 
		// 				JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id 
		// 				ORDER BY `lead_id`,t2.sort,t2.id
		// 			) AS t
		// 		) AS t1				
		// 		WHERE last_stage_id IS NOT null	GROUP BY lead_id
		// 	) AS ll_stage ON lead.id=ll_stage.lead_id";
		//print_r($subdql); die();
		//echo $subsql; die();
        // SEARCH VALUE
        // ---------------------------------------
            
		$sql="SELECT lead.id			
			FROM lead 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			LEFT JOIN source ON source.id=lead.source_id 
			LEFT JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			LEFT JOIN lead_opportunity ON lead_opportunity.lead_id=lead.id 
			 $subsqlInner
			WHERE lead.status='1' $subsql GROUP BY lead.id $subsqlHaving";  

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
    
    function get_list($argument=NULL)
    {       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
		$subsqlHaving='';
        //$order_by_str = " ORDER BY  lead.enquiry_date DESC,lead.id DESC ";
        $order_by_str = " ORDER BY lead.id DESC ";
        if($argument['filter_sort_by']!='')
        {			
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 
		if($argument['filter_common_lead_pool']=='Y')
		{
			if($argument['filter_lead_type_wise_stages']){
				//$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
			}
			$subsql.=" AND lead.assigned_user_id IN ('0')";			
		}	
		else if($argument['filter_lead_observer']=='Y')
		{
			if($argument['filter_lead_type_wise_stages']){
				//$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
			}
			if($argument['assigned_observer']){
				$subsql.=" AND lead.assigned_observer IN ('".$argument['assigned_observer']."')";
			}	
		}	
		else if($argument['filter_search_by_id']!='')
		{
			$subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
		}
		else
		{
				
			if($argument['assigned_user']!='')
			{
				$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
			}
			if(strtoupper($argument['filter_followup'])=='AL' || strtoupper($argument['filter_followup'])=='NL' || strtoupper($argument['filter_followup'])=='TL' || strtoupper($argument['filter_followup'])=='PL' || strtoupper($argument['filter_followup'])=='UL')
			{
				$subsql.=" AND lead.assigned_user_id NOT IN ('0')";				
				if($argument['filter_lead_type_wise_stages']){
					$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";					
				}
				
				$today_date=date("Y-m-d");
				if(strtoupper($argument['filter_followup'])=='NL')
				{
					$subsql.=" AND lead.is_followup_date_changed='N'";
				}
				else if(strtoupper($argument['filter_followup'])=='TL')
				{
					$order_by_str = " ORDER BY lead.followup_date ASC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)='".$today_date."')";
				}
				else if(strtoupper($argument['filter_followup'])=='PL')
				{		
					$order_by_str = " ORDER BY lead.followup_date DESC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)<'".$today_date."')";
							
				}
				else if(strtoupper($argument['filter_followup'])=='UL')
				{		
					$order_by_str = " ORDER BY lead.followup_date ASC ";
					$subsql.=" AND (lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)>'".$today_date."')";
							
				}
				else
				{
					
					
				}
				
			}
			else
			{	
				
				/*
				if($argument['filter_search_by_id']!='')
				{
					$subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
				}*/
				//print_r($argument); die();
				if($argument['filter_search_str']!='')
				{

					$subsql .= " AND  (lead.id = '".$argument['filter_search_str']."' || lead.title LIKE '%".$argument['filter_search_str']."%' ||  lead.buying_requirement LIKE '%".$argument['filter_search_str']."%' || cus.contact_person LIKE '%".$argument['filter_search_str']."%' || cus.email LIKE '%".$argument['filter_search_str']."%' || cus.alt_email LIKE '%".$argument['filter_search_str']."%' || cus.mobile LIKE '%".$argument['filter_search_str']."%' || cus.alt_mobile LIKE '%".$argument['filter_search_str']."%' || cus.company_name LIKE '%".$argument['filter_search_str']."%' || cities.name LIKE '%".$argument['filter_search_str']."%' || states.name LIKE '%".$argument['filter_search_str']."%' || countries.name LIKE '%".$argument['filter_search_str']."%')";
				
				}
				
				if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
				{
					$date_filter_by=$argument['date_filter_by'];
					$from = date_display_format_to_db_format($argument['lead_from_date']);  
					$to = date_display_format_to_db_format($argument['lead_to_date']);  
					
					if($date_filter_by=='added_on')
					{
						$date_filter_by_str="lead.enquiry_date";
						$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
						
					}
					else if($date_filter_by=='updated_on')
					{
						$date_filter_by_str="lead.modify_date";
						$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
						$subsqlInner .=" INNER JOIN lead_comment ON lead_comment.lead_id=lead.id";
						$subsql .=" AND (replace(LOWER(lead_comment.title) , ' ','') NOT LIKE '%newleadcreated%') AND (DATE(lead_comment.create_date) BETWEEN '".$from."' AND '".$to."')";
					}
					else if($date_filter_by=='follow_up_date')
					{
						$date_filter_by_str="lead.followup_date";
						$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
					}
					else if($date_filter_by=='follow_up_on')
					{				
						$date_filter_by_str="DATE(lead.followup_date)";
						$subsql.=" AND lead.current_stage_id IN ('1','2')";
					}
					else if($date_filter_by=='quoted_on' || $date_filter_by=='regretted_on' || $date_filter_by=='deal_losted_on' || $date_filter_by=='deal_won_on')
					{
						if($date_filter_by=='quoted_on'){
							$stageid='2';
						}
						else if($date_filter_by=='regretted_on'){
							$stageid='3,6';
						}
						else if($date_filter_by=='deal_losted_on'){
							$stageid='5,7';
						}
						else if($date_filter_by=='deal_won_on'){
							$stageid=4;				
						}				
						$date_filter_by_str="DATE(stage_log2.create_datetime)";
						$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log2 ON stage_log2.lead_id=lead.id AND stage_log2.stage_id IN (".$stageid.")";
					}
					
					//$from = date_display_format_to_db_format($argument['lead_from_date']);  
					//$to = date_display_format_to_db_format($argument['lead_to_date']);  
					//$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
				}
				
				
				

				
				
				if($argument['lead_applicable_for']!='')
				{		
					$is_export_filter='';
					$is_domestic_filter='';
					if($argument['lead_applicable_for']=='E')
					{
						$is_export_filter='Y';
					}
					else if($argument['lead_applicable_for']=='D')
					{				
						$is_domestic_filter='Y';
					}
					else
					{
						$lead_applicable_for_arr=explode(",",$argument['lead_applicable_for']);
						$is_export_filter='Y';
						$is_domestic_filter='Y';
					}
					
					$company=get_company_profile();
					$company_country_id=$company['country_id'];				
					if($is_export_filter=='Y' && $is_domestic_filter=='')
					{
						$subsql.=" AND cus.country_id !='".$company_country_id."'";
					}
					
					if($is_export_filter=='' && $is_domestic_filter=='Y')
					{
						$subsql.=" AND cus.country_id ='".$company_country_id."'";
					}
				}
				

				
				/*
				if($argument['opportunity_stage']!='')
				{
					$subsql_tmp='';
					if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
					{						
						$from = date_display_format_to_db_format($argument['lead_from_date']);  
						$to = date_display_format_to_db_format($argument['lead_to_date']);
						$subsql_tmp=" AND (DATE(stage_log_inner.create_datetime) BETWEEN '".$from."' AND '".$to."')";
					}
					$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN (".$argument['opportunity_stage'].") $subsql_tmp";
				}*/
				
				
				if($argument['opportunity_status']!='')
				{
					//$subsql.=" AND lead.current_status_id IN (".$argument['opportunity_status'].")";
					$subsql_tmp='';
					if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
					{						
						$from = date_display_format_to_db_format($argument['lead_from_date']);  
						$to = date_display_format_to_db_format($argument['lead_to_date']);
						$subsql_tmp=" AND (DATE(status_log_inner.create_datetime) BETWEEN '".$from."' AND '".$to."')";
					}
					$subsqlInner .=" INNER JOIN lead_status_log AS status_log_inner ON status_log_inner.lead_id=lead.id AND status_log_inner.status_id IN (".$argument['opportunity_status'].") $subsql_tmp";
					if($argument['is_hotstar']=='Y')
					{
						$subsql.=" OR lead.is_hotstar='Y'";
					}
				}
				else
				{
					if($argument['is_hotstar']!='')
					{
						if($argument['is_hotstar']=='Y')
						{
							$subsql.=" AND lead.is_hotstar='Y'";
						}			
					}
				}	
				
				if($argument['pending_followup']!='')
				{			
					$curr_date = date('Y-m-d');
					$current_time = strtotime($curr_date);
					$yesterday_date = date('Y-m-d', strtotime('-1 day', $current_time));
					$two_day_before = date('Y-m-d', strtotime('-2 day', $current_time));
					$five_day_before = date('Y-m-d', strtotime('-5 day', $current_time));

					if($argument['pending_followup_for']=='')
					{

						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$curr_date."')";

					}
					else if($argument['pending_followup_for']=='today')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)='".$curr_date."')";
					}
					else if($argument['pending_followup_for']=='yesterday')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)='".$yesterday_date."')";
					}
					else if($argument['pending_followup_for']=='twodaysbefore')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$two_day_before."')";
					}
					else if($argument['pending_followup_for']=='fivedaysbefore')
					{
						
						$subsql.=" AND (lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].") AND DATE(lead.followup_date)<='".$five_day_before."')";

					}			
				}


				if($argument['source_ids']!='')
				{			
					$subsql.=" AND lead.source_id IN (".$argument['source_ids'].")";
				}

				if($argument['filter_like_dsc']!='')
				{			
					$current_stage_ids='';
					$assigned_user_ids='';
					$uri_str=base64_decode($argument['filter_like_dsc']);
					$uri_str_arr=explode("#", $uri_str);
					$assigned_user_ids=$uri_str_arr[0];
					$filter_by=$uri_str_arr[1];		
					
					//$subsql.=" AND lead.assigned_user_id IN (".$assigned_user_ids.")";
					
					if($filter_by=='quoted_leads'){
						//$subsql.=" AND lead.current_stage_id IN (2)";
						if($argument['filter_lead_type_wise_stages']!='')
						{		
							$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";				
						}
						$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN ('2')";
					}
					
				}
				else
				{					
					if($argument['opportunity_stage_filter_type']=='all_stage')
					{
						if($argument['filter_lead_type']=='AL' && $argument['opportunity_stage']=='1')
						{
							$subsql.=" AND lead.current_stage_id IN (".$argument['opportunity_stage'].")";
						}
						else
						{
							if($argument['filter_lead_type']!='' && $argument['filter_lead_type_wise_stages']!='')
							{		
								$subsql.=" AND lead.current_stage_id IN (".$argument['filter_lead_type_wise_stages'].")";
							}
							if($argument['opportunity_stage']!='')
							{
								$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id AND stage_log_inner.stage_id IN (".$argument['opportunity_stage'].")";
							}							
						}
					}
					else
					{						
						$subsql.=" AND ll_stage.stage_id IN (".$argument['opportunity_stage'].")";
						
					}
				}
								
			}
		}	


		$subsqlInner.=" LEFT JOIN 
							(SELECT t1.*
								FROM 
								(
									SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
									FROM 
									(
										SELECT t1.id,t1.lead_id,t1.stage_id,t2.name,t2.sort,t3.total_stage
										FROM `lead_stage_log` as t1 
										JOIN (SELECT * FROM `opportunity_stage` WHERE id NOT IN (3,4,5,6,7) AND `is_active_lead`='Y' AND `is_deleted` = 'N' ORDER BY sort) AS t2 ON t1.stage_id = t2.id
																	
										JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1 
										JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id 
										ORDER BY `lead_id`,t2.sort,t2.id
									) AS t
								) AS t1				
								WHERE last_stage_id IS NOT null	GROUP BY lead_id
							) AS ll_stage ON lead.id=ll_stage.lead_id";
		
		
        // SEARCH VALUE
        // ---------------------------------------
		//echo $subsql; die();
		$sql="SELECT 
			lo.*,
			lo2.*,
			lo3.*,
			lead.id,
			lead.title,
			lead.customer_id,
			lead.source_id,
			lead.assigned_user_id,
			lead.buying_requirement,
			lead.enquiry_date,
			lead.followup_date,
			lead.modify_date,
			lead.current_stage_id,			
			lead.current_stage,
			lead.current_stage_wise_msg,			
			lead.is_hotstar,
			lead.closer_date,
			lead.deal_value,
			lead.deal_value_currency_code,
			opportunity_status.name AS current_status,
			user.name AS assigned_user_name,
			observer.name AS assigned_observer_name,
			c_paying_info.*,
			c_r_c.*,
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
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,
			source.name AS source_name,
			source.alias_name AS source_alias_name,
			im_setting.account_name AS im_account_name,
			GROUP_CONCAT(stage_log.stage_id ORDER BY stage_log.id) AS stage_logs,
			GROUP_CONCAT(DISTINCT CONCAT(tagged_ps.id,'#',tagged_ps.name)) AS tagged_ps,
			COUNT(DISTINCT note.id) AS note_count,
			coalesce(meeting.meeting_count,0) as meeting_count,
			coalesce(meeting.meeting_completed_count,0) as meeting_completed_count,
			coalesce(call_history.call_count,0) as call_count,
			coalesce(call_history.talked_call_count,0) as talked_call_count,
			ll_stage.name AS ll_stage_name,
			ll_stage.stage_id AS ll_stage_id
			FROM lead 
			LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead WHERE status='1' GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id
			LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			LEFT JOIN source ON source.id=lead.source_id 
			LEFT JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN user AS observer ON observer.id=lead.assigned_observer
			LEFT JOIN (
				SELECT 
				COUNT(id) AS proposal,
				lead_id AS lo_lid,
				SUM(deal_value) AS total_deal_value,
				SUM(deal_value_as_per_purchase_order) AS total_deal_value_as_per_purchase_order
				FROM lead_opportunity GROUP BY lead_id
			) AS lo ON lo.lo_lid=lead.id
			LEFT JOIN (
				SELECT lead_id AS lo_lid2,
				SUBSTRING_INDEX(GROUP_CONCAT(deal_value),',', -1) AS quotation_sent_deal_value,
				currency.code AS quotation_sent_currency_code
				FROM lead_opportunity 
				INNER JOIN currency ON lead_opportunity.currency_type=currency.id 
				WHERE status='2' GROUP BY lead_id
			) AS lo2 ON lo2.lo_lid2=lead.id
			LEFT JOIN (
				SELECT lead_id AS lo_lid3,
				SUBSTRING_INDEX(GROUP_CONCAT(deal_value),',', -1) AS quotation_matured_deal_value,
				SUBSTRING_INDEX(GROUP_CONCAT(deal_value_as_per_purchase_order),',', -1) AS quotation_matured_deal_value_as_per_purchase_order,
				currency.code AS quotation_matured_currency_code
				FROM lead_opportunity 
				INNER JOIN currency ON lead_opportunity.currency_type=currency.id  
				WHERE status='4' GROUP BY lead_id
			) AS lo3 ON lo3.lo_lid3=lead.id
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			LEFT JOIN indiamart_setting AS im_setting ON lead.im_setting_id=im_setting.id 
			LEFT JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id 
			LEFT JOIN lead_wise_product_service AS tagged_ps ON tagged_ps.lead_id=lead.id AND tagged_ps.tag_type='L' 
			LEFT JOIN lead_wise_note AS note ON lead.id=note.lead_id 
			LEFT JOIN opportunity_status ON lead.current_status_id=opportunity_status.id 
			LEFT JOIN (select lead_id,COUNT(id)
			AS meeting_count,
				COUNT(
					IF(
						status_id = 3,
						id,
						NULL
					)
				) AS meeting_completed_count
				from meeting where lead_id is not null group by lead_id
			) as meeting ON lead.id = meeting.lead_id 
			LEFT JOIN (select T2.tagged_lead_id,COUNT(T1.id)
			AS call_count,
				COUNT(
					IF(
						status != 1,
						T1.id,
						NULL
					)
				) AS talked_call_count
				from tbl_call_history_for_lead_tmp AS T1
				INNER JOIN tbl_call_history_for_lead_tmp_tagged_lead AS T2 on T1.id=T2.call_history_id
				where T2.tagged_lead_id is not null group by T2.tagged_lead_id
			) AS call_history 
			ON lead.id = call_history.tagged_lead_id 
			
			$subsqlInner WHERE lead.status='1' $subsql GROUP BY lead.id $subsqlHaving $order_by_str LIMIT $start,$limit";
		//  echo $sql;die();
        $query = $this->client_db->query($sql,false);        
        $last_query = $this->client_db->last_query();
        //return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
    }

    function get_list_count__old($argument=NULL)
    {
        $subsql = ''; 
		$subsqlInner='';
        // ---------------------------------------
        // SEARCH VALUE 

		if($argument['filter_search_by_id']!='')
        {
			$subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
        }

        if($argument['filter_search_str']!='')
        {
            $subsql .= " AND  (lead.id = '".$argument['filter_search_str']."' || lead.title LIKE '%".$argument['filter_search_str']."%' ||  lead.buying_requirement LIKE '%".$argument['filter_search_str']."%' || cus.contact_person LIKE '%".$argument['filter_search_str']."%' || cus.email LIKE '%".$argument['filter_search_str']."%' || cus.mobile LIKE '%".$argument['filter_search_str']."%' || cus.company_name LIKE '%".$argument['filter_search_str']."%' || cust_city.name LIKE '%".$argument['filter_search_str']."%')";

			$subsqlInner .='LEFT JOIN cities AS cust_city ON cust_city.id=cus.city';
        }
		
		if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
		{
			$date_filter_by=$argument['date_filter_by'];
			if($date_filter_by=='added_on')
			{
				$date_filter_by_str="lead.enquiry_date";
			}
			else if($date_filter_by=='updated_on')
			{
				$date_filter_by_str="lead.modify_date";
			}
			else if($date_filter_by=='follow_up_on')
			{
				$date_filter_by_str="lead.followup_date";
				$subsql.=" AND lead.current_stage_id IN ('1','2')";
			}
			else if($date_filter_by=='quoted_on' || $date_filter_by=='regretted_on' || $date_filter_by=='deal_losted_on' || $date_filter_by=='deal_won_on')
			{
				if($date_filter_by=='quoted_on'){
					$stageid='2';
				}
				else if($date_filter_by=='regretted_on'){
					$stageid='3,6';
				}
				else if($date_filter_by=='deal_losted_on'){
					$stageid='5,7';
				}
				else if($date_filter_by=='deal_won_on'){
					$stageid=4;				
				}				
				$date_filter_by_str="DATE(stage_log.create_datetime)";
				$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id AND stage_log.stage_id IN (".$stageid.")";
			}
			
			$from = date_display_format_to_db_format($argument['lead_from_date']);  
			$to = date_display_format_to_db_format($argument['lead_to_date']);  
			$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
		}
		
		
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}
		
		if($argument['lead_applicable_for']!='')
		{		
			$is_export_filter='';
			$is_domestic_filter='';
			if($argument['lead_applicable_for']=='E')
			{
				$is_export_filter='Y';
			}
			else if($argument['lead_applicable_for']=='D')
			{				
				$is_domestic_filter='Y';
			}
			else
			{
				$lead_applicable_for_arr=explode(",",$argument['lead_applicable_for']);
				$is_export_filter='Y';
				$is_domestic_filter='Y';
			}
			
			$company=get_company_profile();
			$company_country_id=$company['country_id'];				
			if($is_export_filter=='Y' && $is_domestic_filter=='')
			{
				$subsql.=" AND cus.country_id !='".$company_country_id."'";
			}
			
			if($is_export_filter=='' && $is_domestic_filter=='Y')
			{
				$subsql.=" AND cus.country_id ='".$company_country_id."'";
			}
		}
		

		if($argument['opportunity_stage']!='')
		{			
			$subsql.=" AND lead.current_stage_id IN (".$argument['opportunity_stage'].")";
		}


		
		if($argument['opportunity_status']!='')
		{
			$subsql.=" AND lead.current_status_id IN (".$argument['opportunity_status'].")";
			if($argument['is_hotstar']=='Y')
			{
				$subsql.=" OR lead.is_hotstar='Y'";
			}
		}
		else
		{
			if($argument['is_hotstar']!='')
			{
				if($argument['is_hotstar']=='Y')
				{
					$subsql.=" AND lead.is_hotstar='Y'";
				}			
			}
		}	
		
		if($argument['pending_followup']!='')
		{			
			$curr_date = date('Y-m-d');
			$current_time = strtotime($curr_date);
			$yesterday_date = date('Y-m-d', strtotime('-1 day', $current_time));
			$two_day_before = date('Y-m-d', strtotime('-2 day', $current_time));
			$five_day_before = date('Y-m-d', strtotime('-5 day', $current_time));

			if($argument['pending_followup_for']=='')
			{
				$subsql.=" AND (lead.current_stage_id IN ('1','8','2','9') AND lead.followup_date<='".$curr_date."')";
			}
			else if($argument['pending_followup_for']=='today')
			{
				$subsql.=" AND (lead.current_stage_id IN ('1','8','2','9') AND lead.followup_date='".$curr_date."')";
			}
			else if($argument['pending_followup_for']=='yesterday')
			{
				$subsql.=" AND (lead.current_stage_id IN ('1','8','2','9') AND lead.followup_date='".$yesterday_date."')";
			}
			else if($argument['pending_followup_for']=='twodaysbefore')
			{
				$subsql.=" AND (lead.current_stage_id IN ('1','8','2','9') AND lead.followup_date<='".$two_day_before."')";
			}
			else if($argument['pending_followup_for']=='fivedaysbefore')
			{
				$subsql.=" AND (lead.current_stage_id IN ('1','8','2','9') AND lead.followup_date<='".$five_day_before."')";
			}			
		}

		if($argument['source_ids']!='')
		{			
			$subsql.=" AND lead.source_id IN (".$argument['source_ids'].")";
		}

		if($argument['filter_like_dsc']!='')
		{			
			$current_stage_ids='';
			$assigned_user_ids='';
			$uri_str=base64_decode($argument['filter_like_dsc']);
			$uri_str_arr=explode("#", $uri_str);
			$assigned_user_ids=$uri_str_arr[0];
			$filter_by=$uri_str_arr[1];			

			if($filter_by=='al')
			{
				$current_stage_ids="'1','8','2','9'";				
			}
			else if($filter_by=='ql')
			{
				$current_stage_ids="'2'";				
			}
			else if($filter_by=='pl')
			{
				$current_stage_ids="'1'";				
			}
			else if($filter_by=='fl')
			{
				$company=get_company_profile();
				$company_country_id=$company['country_id'];
				$current_stage_ids="'1','2'";	
				$subsql.=" AND cus.country_id !=".$company_country_id."";
			}
			else if($filter_by=='dl')
			{
				$company=get_company_profile();
				$company_country_id=$company['country_id'];
				$current_stage_ids="'1','2'";	
				$subsql.=" AND cus.country_id=".$company_country_id."";
			}
			else if($filter_by=='sl')
			{
				$current_stage_ids="'1','2'";	
				$subsql.=" AND lead.is_hotstar='Y' AND lead.status='1'";
			}
			else if($filter_by=='pfl')
			{
				$today_date=date("Y-m-d");				
				$current_stage_ids="'1','8','2','9'";	
				$subsql.=" AND lead.followup_date<='".$today_date."' AND lead.status='1'";
			}

			$subsql.=" AND lead.current_stage_id IN (".$current_stage_ids.")";
			$subsql.=" AND lead.assigned_user_id IN (".$assigned_user_ids.")";
		}
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT lead.id			
			FROM lead 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			INNER JOIN source ON source.id=lead.source_id 
			INNER JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN lead_opportunity ON lead_opportunity.lead_id=lead.id $subsqlInner
			WHERE lead.status='1' $subsql GROUP BY lead.id";  

        $query = $this->client_db->query($sql,false);     
        if($query->num_rows() > 0) {
            return $query->num_rows();
        }
        else {
            return 0;
        }
    }
    function get_list__old($argument=NULL)
    {
       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
        $order_by_str = " ORDER BY  lead.enquiry_date DESC,lead.id DESC ";
        if($argument['filter_sort_by']!='')
        {
			/*
        	if($argument['filter_sort_by']=='P_L_M')
            	$order_by_str = " ORDER BY  t1.date_modified DESC ";
            else if($argument['filter_sort_by']=='P_H_TO_L')
            	$order_by_str = " ORDER BY  t1.price DESC";
            else if($argument['filter_sort_by']=='P_L_TO_H')
            	$order_by_str = " ORDER BY  t1.price ASC";
			*/
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 

        if($argument['filter_search_by_id']!='')
        {
			$subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
        }

        if($argument['filter_search_str']!='')
        {

			$subsql .= " AND  (lead.id = '".$argument['filter_search_str']."' || lead.title LIKE '%".$argument['filter_search_str']."%' ||  lead.buying_requirement LIKE '%".$argument['filter_search_str']."%' || cus.contact_person LIKE '%".$argument['filter_search_str']."%' || cus.email LIKE '%".$argument['filter_search_str']."%' || cus.mobile LIKE '%".$argument['filter_search_str']."%' || cus.company_name LIKE '%".$argument['filter_search_str']."%' || cust_city.name LIKE '%".$argument['filter_search_str']."%')";

			$subsqlInner .='LEFT JOIN cities AS cust_city ON cust_city.id=cus.city';
        }
		
		if($argument['lead_to_date']!='' && $argument['lead_from_date']!='')
		{
			$date_filter_by=$argument['date_filter_by'];
			if($date_filter_by=='added_on')
			{
				$date_filter_by_str="lead.enquiry_date";
			}
			else if($date_filter_by=='updated_on')
			{
				$date_filter_by_str="lead.modify_date";
			}
			else if($date_filter_by=='follow_up_on')
			{				
				$date_filter_by_str="lead.followup_date";
				$subsql.=" AND lead.current_stage_id IN ('1','2')";
			}
			else if($date_filter_by=='quoted_on' || $date_filter_by=='regretted_on' || $date_filter_by=='deal_losted_on' || $date_filter_by=='deal_won_on')
			{
				if($date_filter_by=='quoted_on'){
					$stageid='2';
				}
				else if($date_filter_by=='regretted_on'){
					$stageid='3,6';
				}
				else if($date_filter_by=='deal_losted_on'){
					$stageid='5,7';
				}
				else if($date_filter_by=='deal_won_on'){
					$stageid=4;				
				}				
				$date_filter_by_str="DATE(stage_log.create_datetime)";
				$subsqlInner .=" INNER JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id AND stage_log.stage_id IN (".$stageid.")";
			}
			
			$from = date_display_format_to_db_format($argument['lead_from_date']);  
			$to = date_display_format_to_db_format($argument['lead_to_date']);  
			$subsql.=" AND (".$date_filter_by_str." BETWEEN '".$from."' AND '".$to."') ";
		}
		
		
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}
		
		if($argument['lead_applicable_for']!='')
		{		
			$is_export_filter='';
			$is_domestic_filter='';
			if($argument['lead_applicable_for']=='E')
			{
				$is_export_filter='Y';
			}
			else if($argument['lead_applicable_for']=='D')
			{				
				$is_domestic_filter='Y';
			}
			else
			{
				$lead_applicable_for_arr=explode(",",$argument['lead_applicable_for']);
				$is_export_filter='Y';
				$is_domestic_filter='Y';
			}
			
			$company=get_company_profile();
			$company_country_id=$company['country_id'];				
			if($is_export_filter=='Y' && $is_domestic_filter=='')
			{
				$subsql.=" AND cus.country_id !='".$company_country_id."'";
			}
			
			if($is_export_filter=='' && $is_domestic_filter=='Y')
			{
				$subsql.=" AND cus.country_id ='".$company_country_id."'";
			}
		}
		

		if($argument['opportunity_stage']!='')
		{			
			$subsql.=" AND lead.current_stage_id IN (".$argument['opportunity_stage'].")";
		}
		
		if($argument['opportunity_status']!='')
		{
			$subsql.=" AND lead.current_status_id IN (".$argument['opportunity_status'].")";
			if($argument['is_hotstar']=='Y')
			{
				$subsql.=" OR lead.is_hotstar='Y'";
			}
		}
		else
		{
			if($argument['is_hotstar']!='')
			{
				if($argument['is_hotstar']=='Y')
				{
					$subsql.=" AND lead.is_hotstar='Y'";
				}			
			}
		}	
		
		if($argument['pending_followup']!='')
		{			
			$curr_date = date('Y-m-d');
			$current_time = strtotime($curr_date);
			$yesterday_date = date('Y-m-d', strtotime('-1 day', $current_time));
			$two_day_before = date('Y-m-d', strtotime('-2 day', $current_time));
			$five_day_before = date('Y-m-d', strtotime('-5 day', $current_time));

			if($argument['pending_followup_for']=='')
			{
				$subsql.=" AND (lead.current_stage_id IN ('1','8','2','9') AND lead.followup_date<='".$curr_date."')";
			}
			else if($argument['pending_followup_for']=='today')
			{
				$subsql.=" AND (lead.current_stage_id IN ('1','8','2','9') AND lead.followup_date='".$curr_date."')";
			}
			else if($argument['pending_followup_for']=='yesterday')
			{
				$subsql.=" AND (lead.current_stage_id IN ('1','8','2','9') AND lead.followup_date='".$yesterday_date."')";
			}
			else if($argument['pending_followup_for']=='twodaysbefore')
			{
				$subsql.=" AND (lead.current_stage_id IN ('1','8','2','9') AND lead.followup_date<='".$two_day_before."')";
			}
			else if($argument['pending_followup_for']=='fivedaysbefore')
			{
				$subsql.=" AND (lead.current_stage_id IN ('1','2') AND lead.followup_date<='".$five_day_before."')";
			}			
		}


		if($argument['source_ids']!='')
		{			
			$subsql.=" AND lead.source_id IN (".$argument['source_ids'].")";
		}

		if($argument['filter_like_dsc']!='')
		{			
			$current_stage_ids='';
			$assigned_user_ids='';
			$uri_str=base64_decode($argument['filter_like_dsc']);
			$uri_str_arr=explode("#", $uri_str);
			$assigned_user_ids=$uri_str_arr[0];
			$filter_by=$uri_str_arr[1];			

			if($filter_by=='al')
			{
				$current_stage_ids="'1','8','2','9'";				
			}
			else if($filter_by=='ql')
			{
				$current_stage_ids="'2'";				
			}
			else if($filter_by=='pl')
			{
				$current_stage_ids="'1'";				
			}
			else if($filter_by=='fl')
			{
				$company=get_company_profile();
				$company_country_id=$company['country_id'];
				$current_stage_ids="'1','2'";	
				$subsql.=" AND cus.country_id !=".$company_country_id."";
			}
			else if($filter_by=='dl')
			{
				$company=get_company_profile();
				$company_country_id=$company['country_id'];
				$current_stage_ids="'1','2'";	
				$subsql.=" AND cus.country_id=".$company_country_id."";
			}
			else if($filter_by=='sl')
			{
				$current_stage_ids="'1','2'";	
				$subsql.=" AND lead.is_hotstar='Y' AND lead.status='1'";
			}
			else if($filter_by=='pfl')
			{
				$today_date=date("Y-m-d");				
				$current_stage_ids="'1','8','2','9'";	
				$subsql.=" AND lead.followup_date<='".$today_date."' AND lead.status='1'";
			}

			$subsql.=" AND lead.current_stage_id IN (".$current_stage_ids.")";
			$subsql.=" AND lead.assigned_user_id IN (".$assigned_user_ids.")";
		}
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			COUNT(DISTINCT(lead_opportunity.id)) AS proposal,
			lead.id,
			lead.title,
			lead.customer_id,
			lead.assigned_user_id,
			lead.buying_requirement,
			lead.enquiry_date,
			lead.followup_date,
			lead.modify_date,
			lead.current_stage_id,			
			lead.current_stage,
			lead.current_status,
			lead.is_hotstar,
			user.name AS assigned_user_name,
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
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,
			source.name AS source_name,
			im_setting.account_name AS im_account_name,
			GROUP_CONCAT(stage_log.stage_id ORDER BY stage_log.id) AS stage_logs,
			GROUP_CONCAT(DISTINCT CONCAT(tagged_ps.id,'#',tagged_ps.name)) AS tagged_ps
			FROM lead 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			INNER JOIN source ON source.id=lead.source_id 
			INNER JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN lead_opportunity ON lead_opportunity.lead_id=lead.id 
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			LEFT JOIN indiamart_setting AS im_setting ON lead.im_setting_id=im_setting.id 
			LEFT JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id 
			LEFT JOIN lead_wise_product_service AS tagged_ps ON tagged_ps.lead_id=lead.id  $subsqlInner
			WHERE lead.status='1' $subsql GROUP BY lead.id $order_by_str LIMIT $start,$limit";
		//echo $sql;die();
        $query = $this->client_db->query($sql,false);        
        $last_query = $this->client_db->last_query();
        //return $query->result();

		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
    }
	// ADDED BY ARUP KUMAR POREL
	// ============================================

	public function GetHistoryForLeadUpdateComments($lead_id,$user_id)
	{
		$sql="SELECT 
		t1.id,
		t1.comment,
		t1.create_date,
		t2.user_type,t2.name AS user_name,
		t2.email AS user_email 
		FROM lead_comment AS t1 
		INNER JOIN user AS t2 ON t1.user_id=t2.id
		WHERE t1.lead_id='".$lead_id."' 
		AND t1.mail_to_client IN ('Y') 
		AND t1.user_id='".$user_id."' 
		AND comment NOT LIKE '%REGRETTED%' 
		AND comment NOT LIKE '%DEAL LOST%' 
		ORDER BY t1.create_date DESC";
		$query = $this->client_db->query($sql,false);        
        $last_query = $this->client_db->last_query();
        //return $query->result();

		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}

	public function GetLeadCommentsById($ids)
	{
		$sql="SELECT 
		t1.id,
		t1.comment,
		t1.create_date,
		t2.user_type,t2.name AS user_name,
		t2.email AS user_email 
		FROM lead_comment AS t1 
		INNER JOIN user AS t2 ON t1.user_id=t2.id
		WHERE t1.id IN ($ids)  
		ORDER BY t1.create_date DESC";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->result();

		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}

	public function add_fb_ig($data)
	{
		if($this->client_db->insert('lead_fb_ig_tmp',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function truncate_fb_ig()
	{

		if($this->client_db->truncate('lead_fb_ig_tmp'))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}

	public function get_fb_ig_csv()
	{
		$sql="SELECT * FROM lead_fb_ig_tmp ORDER BY tmp_id";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	function get_decision($arg=array())
	{
		$email=$arg['email'];
		$mobile=$arg['mobile'];		
		if($email!='' || $mobile!='')
		{
			if($email!='')
			{
				$sql="SELECT id FROM customer WHERE status='1' AND email='".$email."'";
				$query=$this->client_db->query($sql);		
				if($query->num_rows()==0)
				{		
					if($mobile!='')
					{
						$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
						$query=$this->client_db->query($sql);		
						if($query->num_rows()==0)
						{			
							return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
								
						}
						else if($query->num_rows()==1)
						{
							$row=$query->row();
							return array('msg'=>'one_customer_exist','customer_id'=>$row->id,'status'=>TRUE);
						}											
					}
					else
					{
						return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
					}					
				}
				else if($query->num_rows()==1)
				{
					$row=$query->row();
					return array('msg'=>'one_customer_exist','customer_id'=>$row->id,'status'=>TRUE);	
				}						
			}	

			if($mobile!='')
			{
				$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
				$query=$this->client_db->query($sql);		
				if($query->num_rows()==0)
				{			
					return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
						
				}
				else if($query->num_rows()==1)
				{
					$row=$query->row();
					return array('msg'=>'one_customer_exist','customer_id'=>$row->id,'status'=>TRUE);
				}											
			}		
		}
		else
		{
			return array('msg'=>'SENDEREMAIL and MOB both missing','customer_id'=>'','status'=>FALSE);
		}					
	}

	public function get_fb_ig_tmp_list($user_id)
	{
		$sql="SELECT tmp_id,
		user_id,
		uploaded_datetime,
		id,
		created_time,
		ad_id,
		ad_name,
		adset_id,
		adset_name,
		campaign_id,
		campaign_name,
		form_id,
		form_name,
		is_organic,
		platform,
		your_mob_no,
		full_name,
		email,
		phone_number,
		city,
		assigned_user_employee_id 
		FROM lead_fb_ig_tmp WHERE user_id='".$user_id."' ORDER BY tmp_id";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function fb_ig_data_chk($user_id)
	{
		$sql="SELECT 
		id,
		tmp_id,
		ad_name,
		campaign_name,	
		platform,	
		your_mob_no,
		full_name,
		email,
		phone_number,
		city,
		assigned_user_employee_id 
		FROM lead_fb_ig_tmp WHERE user_id='".$user_id."' 
		ORDER BY tmp_id";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        $error_log=[];
        if($query->num_rows() > 0) 
        {
        	$rows=$query->result();
        	foreach($rows AS $row)
        	{
        		if($row->id=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'id',
        										'msg'=>'Missing id'
        										);
        		}

        		if($row->ad_name=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'ad_name',
        										'msg'=>'Missing ad_name'
        										);
        		}

        		if($row->campaign_name=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'campaign_name',
        										'msg'=>'Missing campaign_name'
        										);
        		}        		

        		if($row->platform!='')
        		{
        			$p_arr=array('fb','ig');
        			if (!in_array($row->platform, $p_arr))
        			{
        				$error_log[$row->tmp_id][]=array('keyword'=>'platform',
        										'msg'=>'platform should be fb/ig'
        										);
        			}
        		}
        		else
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'platform',
        										'msg'=>'Missing platform'
        										);
        		}

				if($row->your_mob_no=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'your_mob_no',
        										'msg'=>'Missing your_mob_no'
        										);
        		}

        		if($row->full_name=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'full_name',
        										'msg'=>'Missing full_name'
        										);
        		}

				if($row->email=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'email',
        										'msg'=>'Missing email'
        										);
        		}

        		if($row->phone_number=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'phone_number',
        										'msg'=>'Missing phone_number'
        										);
        		}


        		if($row->city!='')
        		{
        			$fb_ig_city=strtolower(str_replace(' ', '', $row->city));        			
	        		// city checking
	        		$sql_city="SELECT id,name FROM cities 
	        					WHERE LOWER(replace(name , ' ',''))='".$fb_ig_city."'";
	        		$query_city = $this->client_db->query($sql_city,false);   
	        		if($query_city->num_rows() == 0) 
	        		{
	        			$error_log[$row->tmp_id][]=array('keyword'=>'city',
        										'msg'=>'City name not found in the system'
        										);
	        		}
	        	}
        		else
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'city',
        										'msg'=>'Missing city name'
        										);
        		}

        		if($row->assigned_user_employee_id!='')
        		{
        			$fb_ig_emp_id=str_replace(' ', '', $row->assigned_user_employee_id);
	        		// assigned_user_employee_id checking
	        		$sql_user="SELECT id FROM user 
	        					WHERE id='".$fb_ig_emp_id."'";
	        		$query_user = $this->client_db->query($sql_user,false);   
	        		if($query_user->num_rows() == 0) 
	        		{
	        			$error_log[$row->tmp_id][]=array('keyword'=>'assigned_user_employee_id',
        										'msg'=>'employee id not found in the system'
        										);
	        		}
        		}
        		else
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'assigned_user_employee_id',
        										'msg'=>'Missing assigned_user_employee_id'
        										);
        		}	        	
        	}
        	
        }
        return $error_log;       
	}

	public function get_city_by_name($name)
	{
		$city_tmp=strtolower(str_replace(' ', '', $name));
		$sql="SELECT id,
		name,
		state_id 
		FROM cities WHERE LOWER(replace(name , ' ',''))='".$city_tmp."' LIMIT 0,1";
		$query = $this->client_db->query($sql,false);   
		if($query->num_rows()>0) 
		{       
	        //echo $last_query = $this->client_db->last_query();
	        return $query->row_array();
		} else {
			return array();
		}

		
	}

	public function get_country_by_name($name)
	{
		$country_tmp=strtolower(str_replace(' ', '', $name));
		$sql="SELECT id,
		sortname,
		name,
		phonecode  
		FROM countries WHERE LOWER(replace(name , ' ',''))='".$country_tmp."' LIMIT 0,1";
		$query = $this->client_db->query($sql,false);   
		if($query->num_rows()>0) 
		{       
	        //echo $last_query = $this->client_db->last_query();
	        return $query->row_array();
		} else {
			return array();
		}
	}

	public function get_source_id($name)
	{
		$name_tmp=strtolower(str_replace(' ', '', $name));
		$sql="SELECT id,name
		FROM source WHERE LOWER(replace(name , ' ',''))='".$name_tmp."' LIMIT 0,1";
		$query = $this->client_db->query($sql,false);   
		if($query->num_rows()>0) 
		{       
	        //echo $last_query = $this->client_db->last_query();
	        $row=$query->row();
	        return $row->id;
		}
		else
		{
			$data=array('parent_id'=>0,'name'=>$name);
			if($this->client_db->insert('source',$data))
	   		{
	           return $this->client_db->insert_id();
	   		}
	   		else
	   		{
	          return false;
	   		}
		}
	}

	public function delete_fb_ig_tmp_by_user($user_id)
	{
		$this->client_db -> where('user_id', $user_id);
    	$this->client_db -> delete('lead_fb_ig_tmp');
	}

	function update_fb_ig_tmp($data,$id)
	{
		$this->client_db->where('tmp_id',$id);
		if($this->client_db->update('lead_fb_ig_tmp',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function lead_chk_by_fbigId($fb_ig_id='')
	{
		if($fb_ig_id!='')
		{
			$sql="SELECT id FROM lead WHERE fb_ig_id='".$fb_ig_id."'";
			$query = $this->client_db->query($sql,false);   
			if($query->num_rows()>0)
			{
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			return TRUE;
		}
	}

	public function get_fb_ig_tmp_list_for_mail_send()
	{
		$sql="SELECT tmp_id,
		user_id,
		uploaded_datetime,
		id,
		created_time,
		ad_id,
		ad_name,
		adset_id,
		adset_name,
		campaign_id,
		campaign_name,
		form_id,
		form_name,
		is_organic,
		platform,
		your_mob_no,
		full_name,
		email,
		phone_number,
		city,
		assigned_user_employee_id,
		lead_id,
		is_added_as_lead
		FROM lead_fb_ig_tmp WHERE lead_id>'0' AND is_added_as_lead='Y' AND is_mail_sent='N' ORDER BY uploaded_datetime";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function delete_fb_ig_tmp_which_email_sent()
	{
		$this->client_db -> where('is_mail_sent', 'Y');
    	$this->client_db -> delete('lead_fb_ig_tmp');
	}


	public function delete_lead_csv_upload_tmp_by_user($user_id)
	{
		$this->client_db -> where('user_id', $user_id);
    	$this->client_db -> delete('lead_csv_upload_tmp');
	}

	public function add_csv_upload_tmp($data)
	{
		if($this->client_db->insert('lead_csv_upload_tmp',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function update_csv_upload_tmp($data,$id)
	{
		$this->client_db->where('tmp_id',$id);
		if($this->client_db->update('lead_csv_upload_tmp',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function csv_upload_tmp_data_chk($user_id)
	{
		$sql="SELECT 
		t1.tmp_id,
		t1.lead_title,
		t1.lead_describe_requirement,
		t1.lead_source,
		t1.assigned_user_employee_id,	
		t1.company_name,	
		t1.company_contact_person,
		t1.company_email,
		t1.company_mobile,
		t1.company_city,
		t1.company_country  
		FROM lead_csv_upload_tmp AS t1 WHERE t1.user_id='".$user_id."' 
		ORDER BY t1.tmp_id";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        $error_log=[];
        if($query->num_rows() > 0) 
        {
        	$rows=$query->result();
        	foreach($rows AS $row)
        	{
        		if($row->lead_title=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'lead_title',
        										'msg'=>'Missing lead_title'
        										);
        		}

        		if($row->lead_describe_requirement=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'lead_describe_requirement',
        										'msg'=>'Missing lead_describe_requirement'
        										);
        		}

        		if($row->lead_source=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'lead_source',
        										'msg'=>'Missing lead_source'
        										);
        		}  
        		else
        		{
        			$source_arr=[];
            		$get_source=get_source();
            		$source_str ='';            		
            		if(count($get_source))
            		{
            			foreach($get_source AS $rource)
            			{
							$rource_name_tmp=strtolower(str_replace(' ', '', $rource['name']));
            				array_push($source_arr, $rource_name_tmp);
            			}
            			if (!in_array(strtolower(str_replace(' ', '', $row->lead_source)), $source_arr))
	        			{
	        				$error_log[$row->tmp_id][]=array('keyword'=>'lead_source',
	        										'msg'=>'lead_source not found in the system'
	        										);
	        			}
            		}
        		}


        		if($row->assigned_user_employee_id!='')
        		{
        			$emp_id=str_replace(' ', '', $row->assigned_user_employee_id);
	        		// assigned_user_employee_id checking
	        		/*
	        		$sql_user="SELECT id FROM user 
	        					WHERE id='".$emp_id."'";
	        		$query_user = $this->client_db->query($sql_user,false);   
	        		if($query_user->num_rows() == 0) 
	        		{
	        			$error_log[$row->tmp_id][]=array('keyword'=>'assigned_user_employee_id',
        										'msg'=>'employee id not found in the system'
        										);
	        		}*/
        		}
        		else
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'assigned_user_employee_id',
        										'msg'=>'Missing assigned_user_employee_id'
        										);
        		}

				// if($row->company_name=='')
				// {
				// $error_log[$row->tmp_id][]=array('keyword'=>'company_name',
				// 'msg'=>'Missing company_name'
				// );
				// }

        		if($row->company_contact_person=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'company_contact_person',
        										'msg'=>'Missing company_contact_person'
        										);
        		}

        		if($row->company_city!='')
        		{
        			// $city_tmp=strtolower(str_replace(' ', '', $row->company_city));        			
	        		// // city checking
	        		// $sql_city="SELECT name FROM cities 
	        		// 			WHERE LOWER(replace(name , ' ',''))='".$city_tmp."'";
	        		// $query_city = $this->client_db->query($sql_city,false);   
	        		// if($query_city->num_rows() == 0) 
	        		// {
	        		// 	$error_log[$row->tmp_id][]=array('keyword'=>'company_city',
        			// 							'msg'=>'City name not found in the system'
        			// 							);
	        		// }
	        	}
        		else
        		{
        			// $error_log[$row->tmp_id][]=array('keyword'=>'company_city',
        			// 							'msg'=>'Missing company_city name'
        			// 							);
        		} 
				
				if($row->company_country!='')
        		{
        			$country_tmp=strtolower(str_replace(' ', '', $row->company_country));        			
	        		// country checking
	        		$sql_country="SELECT name FROM countries 
	        					WHERE LOWER(replace(name , ' ',''))='".$country_tmp."'";
	        		$query_country = $this->client_db->query($sql_country,false);   
	        		if($query_country->num_rows() == 0) 
	        		{
	        			$error_log[$row->tmp_id][]=array('keyword'=>'company_country',
        										'msg'=>'Country name not found in the system'
        										);
	        		}
	        	}
        		else
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'company_country',
        										'msg'=>'Missing country name'
        										);
        		} 
        	}        	
        }
        return $error_log;       
	}

	public function get_csv_upload_tmp_list($user_id)
	{
		$sql="SELECT tmp_id,
		lead_title,
		lead_describe_requirement,
		lead_enquiry_date,
		lead_next_followup_date,
		lead_source,
		assigned_user_employee_id,
		company_name,
		company_contact_person,
		company_contact_person_designation,
		company_email,
		company_alternate_email,
		company_mobile,
		company_alternate_mobile,
		company_landline_number,
		company_address,
		company_city,
		company_country,
		company_zip,
		company_website,
		company_short_description,
		reference_name,
		user_id,
		uploaded_datetime,
		lead_id,
		is_added_as_lead,
		is_mail_sent 
		FROM lead_csv_upload_tmp WHERE user_id='".$user_id."' ORDER BY tmp_id";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function get_csv_upload_tmp_list_for_mail_send()
	{
		$sql="SELECT tmp_id,
		lead_title,
		lead_describe_requirement,
		lead_enquiry_date,
		lead_next_followup_date,
		lead_source,
		assigned_user_employee_id,
		company_name,
		company_contact_person,
		company_contact_person_designation,
		company_email,
		company_alternate_email,
		company_mobile,
		company_alternate_mobile,
		company_landline_number,
		company_address,
		company_city,
		company_zip,
		company_website,
		company_short_description,
		user_id,
		uploaded_datetime,
		lead_id,
		is_added_as_lead,
		is_mail_sent
		FROM lead_csv_upload_tmp WHERE lead_id>'0' AND is_added_as_lead='Y' AND is_mail_sent='N' ORDER BY uploaded_datetime";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function delete_csv_upload_tmp_which_email_sent()
	{
		$this->client_db->where('is_mail_sent','Y');
    	$this->client_db->delete('lead_csv_upload_tmp');
	}

	public function is_exist_uid($uid)
	{
		$sql="SELECT id FROM gmail_inbox_overview WHERE uid='".$uid."'";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();        
        if($query->num_rows()==0) 
        {
        	return 'N';
        }
        else
        {
        	return 'Y';
        }
	}

	public function gmail_overview_add($data)
	{
		if($this->client_db->insert('gmail_inbox_overview',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function gmail_overview_update($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('gmail_inbox_overview',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	public function gmail_header_add($data)
	{
		if($this->client_db->insert('gmail_inbox_header',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function gmail_attachment_add($data)
	{
		if($this->client_db->insert('gmail_inbox_attachment',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}


	function get_gmail_data_count($argument=NULL)
    {
        $subsql = ''; 
		$subsqlInner='';
		
        // ---------------------------------------
        // SEARCH VALUE 

		// if($argument['filter_search_str']!='')
		// {
		// 	$subsql .= " AND  (lead.title LIKE '".$argument['filter_search_str']."%' OR lead.id='".$argument['filter_search_str']."')";
		// }
		
		if($argument['user_id']!='')
		{
			$subsql.=" AND t1.user_id='".$argument['user_id']."'";
		}

		if($argument['customer_exist_keyword']!='')
		{
			$subsql.=" AND t1.customer_exist_keyword IN (".$argument['customer_exist_keyword'].")";
		}
		
		
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT t1.id
				FROM gmail_inbox_overview AS t1 
				INNER JOIN gmail_inbox_header AS t2 ON t1.id=t2.overview_id
				LEFT JOIN gmail_inbox_attachment AS t3 ON t1.id=t3.overview_id
				WHERE t1.is_deleted='N' $subsql GROUP BY t2.overview_id";

        $query = $this->client_db->query($sql,false);     
        if($query->num_rows() > 0) {
            return $query->num_rows();
        }
        else {
            return 0;
        }
    }
	public function get_gmail_data($argument=NULL)
	{
		$start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
        //$order_by_str = " ORDER BY  lead.id DESC ";
		// ---------------------------------------
        // SEARCH VALUE 

		// if($argument['filter_search_str']!='')
		// {
		// 	$subsql .= " AND  (lead.title LIKE '".$argument['filter_search_str']."%' OR lead.id='".$argument['filter_search_str']."')";
		// }
		
		if($argument['user_id']!='')
		{
			$subsql.=" AND t1.user_id='".$argument['user_id']."'";
		}
		
		if($argument['customer_exist_keyword']!='')
		{
			$subsql.=" AND t1.customer_exist_keyword IN (".$argument['customer_exist_keyword'].")";
		}
		
        // SEARCH VALUE
        // ---------------------------------------

		$sql="SELECT t1.id,
					t1.uid,
					t1.msgno,
					t1.subject,
					t1.from_name,
					t1.to_mail,
					t1.date,
					t1.message_id,
					t1.size,
					t1.recent,
					t1.flagged,
					t1.answered,
					t1.deleted,
					t1.seen,
					t1.draft,
					t1.udate,
					t1.message,
					t1.user_id,
					t1.customer_id,
					t1.lead_id,
					t1.is_read,
					t1.created_at,
					t1.updated_at,
					t2.id AS h_id,
					t2.date AS h_date,
					t2.subject AS h_subject,
					t2.toaddress AS h_toaddress,
					t2.to_mailbox AS h_to_mailbox,
					t2.to_host AS h_to_host,
					t2.fromaddress AS h_fromaddress,
					t2.from_personal AS h_from_personal,
					t2.from_mailbox AS h_from_mailbox,
					t2.from_host AS h_from_host,
					t2.reply_toaddress AS h_reply_toaddress,
					t2.reply_to_personal AS h_reply_to_personal,
					t2.reply_to_mailbox AS h_reply_to_mailbox,
					t2.reply_to_host AS h_reply_to_host,
					t2.senderaddress AS h_senderaddress,
					t2.sender_personal AS h_sender_personal,
					t2.sender_mailbox AS h_sender_mailbox,
					t2.sender_host AS h_sender_host,
					t2.recent AS h_recent,
					t2.unseen AS h_unseen,
					t2.flagged AS h_flagged,
					t2.answered AS h_answered,
					t2.deleted AS h_deleted,
					t2.draft AS h_draft,
					t2.msgno AS h_msgno,
					t2.mailDate AS h_mailDate,
					t2.size AS h_size,
					t2.udate AS h_udate,
					GROUP_CONCAT(t3.file_name) AS file_name,
					GROUP_CONCAT(t3.file_full_path) AS file_full_path
				FROM gmail_inbox_overview AS t1 
				INNER JOIN gmail_inbox_header AS t2 ON t1.id=t2.overview_id
				LEFT JOIN gmail_inbox_attachment AS t3 ON t1.id=t3.overview_id
				WHERE t1.is_deleted='N'  $subsql GROUP BY t2.overview_id ORDER BY t1.uid DESC LIMIT $start,$limit";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function get_gmail_data_detail($id)
	{
		$sql="SELECT t1.id,
					t1.uid,
					t1.msgno,
					t1.subject,
					t1.from_name,
					t1.to_mail,
					t1.date,
					t1.message_id,
					t1.size,
					t1.recent,
					t1.flagged,
					t1.answered,
					t1.deleted,
					t1.seen,
					t1.draft,
					t1.udate,
					t1.message,
					t1.user_id,
					t1.customer_id,
					t1.lead_id,
					t1.is_read,
					t1.created_at,
					t1.updated_at,
					t2.id AS h_id,
					t2.date AS h_date,
					t2.subject AS h_subject,
					t2.toaddress AS h_toaddress,
					t2.to_mailbox AS h_to_mailbox,
					t2.to_host AS h_to_host,
					t2.fromaddress AS h_fromaddress,
					t2.from_personal AS h_from_personal,
					t2.from_mailbox AS h_from_mailbox,
					t2.from_host AS h_from_host,
					t2.reply_toaddress AS h_reply_toaddress,
					t2.reply_to_personal AS h_reply_to_personal,
					t2.reply_to_mailbox AS h_reply_to_mailbox,
					t2.reply_to_host AS h_reply_to_host,
					t2.senderaddress AS h_senderaddress,
					t2.sender_personal AS h_sender_personal,
					t2.sender_mailbox AS h_sender_mailbox,
					t2.sender_host AS h_sender_host,
					t2.recent AS h_recent,
					t2.unseen AS h_unseen,
					t2.flagged AS h_flagged,
					t2.answered AS h_answered,
					t2.deleted AS h_deleted,
					t2.draft AS h_draft,
					t2.msgno AS h_msgno,
					t2.mailDate AS h_mailDate,
					t2.size AS h_size,
					t2.udate AS h_udate,
					GROUP_CONCAT(t3.file_name) AS file_name,
					GROUP_CONCAT(t3.file_full_path) AS file_full_path
				FROM gmail_inbox_overview AS t1 
				INNER JOIN gmail_inbox_header AS t2 ON t1.id=t2.overview_id
				LEFT JOIN gmail_inbox_attachment AS t3 ON t1.id=t3.overview_id
				WHERE t1.id='".$id."' AND t1.is_deleted='N' GROUP BY t2.overview_id";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        return $query->row_array();

		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	function get_gmail_inbox_overview_id_by_email($email)
	{
		$sql="SELECT GROUP_CONCAT(DISTINCT t1.id) AS gmail_ids
				FROM gmail_inbox_overview AS t1 
				INNER JOIN gmail_inbox_header AS t2 ON t1.id=t2.overview_id
				LEFT JOIN gmail_inbox_attachment AS t3 ON t1.id=t3.overview_id
				WHERE t1.is_deleted='N' AND CONCAT(t2.from_mailbox,'@',t2.from_host)='".$email."' AND t1.customer_exist_keyword='no_customer_exist' 
				GROUP BY CONCAT(t2.from_mailbox,'@',t2.from_host)";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->row()->gmail_ids;

		if($query->num_rows()>0) 
		{
			return $query->row()->gmail_ids;            
        }
        else 
        {
            return 0;
        }
	}

	// -------------------------------------------
	// GMAIL API
	public function is_exist_message_id($mid)
	{
		$sql="SELECT id FROM gmail_data WHERE message_id='".$mid."'";
		$query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query(); die();       
        if($query->num_rows()==0) 
        {
        	return 'N';
        }
        else
        {
        	return 'Y';
        }
	}

	public function gmail_data_add($data)
	{
		if($this->client_db->insert('gmail_data',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function gmail_data_update($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('gmail_data',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function gmail_data_attachment_add($data)
	{
		if($this->client_db->insert('gmail_attachment',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function get_gmail_count($argument=NULL)
    {
        $subsql = ''; 
		$subsqlInner='';
		
        // ---------------------------------------
        // SEARCH VALUE 

		if($argument['filter_search_str']!='')
		{
			$subsql .= " AND  (t1.from_name LIKE '%".$argument['filter_search_str']."%' OR t1.to_name LIKE '%".$argument['filter_search_str']."%' OR t1.subject LIKE '%".$argument['filter_search_str']."%')";
		}
		
		if($argument['filter_by_from']!='')
		{
			$subsql .= " AND  (t1.from_mail LIKE '%".$argument['filter_by_from']."%')";
		}

		if($argument['filter_by_to']!='')
		{
			$subsql .= " AND  (t1.to_mail LIKE '%".$argument['filter_by_to']."%')";
		}

		if($argument['filter_by_subject']!='')
		{
			$subsql .= " AND  (t1.subject LIKE '%".$argument['filter_by_subject']."%')";
		}
		
		if($argument['filter_by_date']!='' && $argument['filter_by_date_to']!='')
		{
			$from_date=date_display_format_to_db_format($argument['filter_by_date']);
			$to_date=date_display_format_to_db_format($argument['filter_by_date_to']);
			$date1 = new DateTime($from_date);
			$date2 = new DateTime($to_date);
			$diff = $date1->diff($date2);
			if($diff->invert==0)
			{
				$subsql.=" AND (from_unixtime(t1.internal_date/1000,'%Y-%m-%d')>='".$from_date."' AND from_unixtime(t1.internal_date/1000,'%Y-%m-%d')<='".$to_date."')";
			}			
		}

		if($argument['user_id']!='')
		{
			$subsql.=" AND t1.user_id='".$argument['user_id']."'";
		}

		if($argument['is_show_linked']!='')
		{
			if($argument['is_show_linked']=='Y')
			{
				$subsql.=" AND t1.lead_id>0";
			}
			else if($argument['is_show_linked']=='N')
			{
				$subsql.=" AND t1.lead_id=0";
			}
		}
		
		
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT t1.id
				FROM gmail_data AS t1 
				LEFT JOIN  gmail_attachment AS t2 ON t1.id=t2.gmail_data_id
				WHERE t1.is_deleted='N'  $subsql GROUP BY t1.thread_id";

        $query = $this->client_db->query($sql,false);     
        if($query->num_rows() > 0) {
            return $query->num_rows();
        }
        else {
            return 0;
        }
    }
	public function get_gmail_list($argument=NULL)
	{
		$start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
        //$order_by_str = " ORDER BY  lead.id DESC ";
		// ---------------------------------------
        // SEARCH VALUE 

		if($argument['filter_search_str']!='')
		{
			$subsql .= " AND  (t1.from_name LIKE '%".$argument['filter_search_str']."%' OR t1.to_name LIKE '%".$argument['filter_search_str']."%' OR t1.subject LIKE '%".$argument['filter_search_str']."%')";
		}

		if($argument['filter_by_from']!='')
		{
			$subsql .= " AND  (t1.from_mail LIKE '%".$argument['filter_by_from']."%')";
		}

		if($argument['filter_by_to']!='')
		{
			$subsql .= " AND  (t1.to_mail LIKE '%".$argument['filter_by_to']."%')";
		}

		if($argument['filter_by_subject']!='')
		{
			$subsql .= " AND  (t1.subject LIKE '%".$argument['filter_by_subject']."%')";
		}
		
		if($argument['filter_by_date']!='' && $argument['filter_by_date_to']!='')
		{
			$from_date=date_display_format_to_db_format($argument['filter_by_date']);
			$to_date=date_display_format_to_db_format($argument['filter_by_date_to']);
			$date1 = new DateTime($from_date);
			$date2 = new DateTime($to_date);
			$diff = $date1->diff($date2);
			if($diff->invert==0)
			{
				$subsql.=" AND (from_unixtime(t1.internal_date/1000,'%Y-%m-%d')>='".$from_date."' AND from_unixtime(t1.internal_date/1000,'%Y-%m-%d')<='".$to_date."')";
			}			
		}


		if($argument['user_id']!='')
		{
			$subsql.=" AND t1.user_id='".$argument['user_id']."'";
		}
		
		if($argument['is_show_linked']!='')
		{
			if($argument['is_show_linked']=='Y')
			{
				$subsql.=" AND t1.lead_id>0";
			}
			else if($argument['is_show_linked']=='N')
			{
				$subsql.=" AND t1.lead_id=0";
			}
		}
		
        // SEARCH VALUE
        // ---------------------------------------

		$sql="SELECT 
					t1.id,
					MIN(t1.id) AS min_id,
					MAX(t1.id) AS max_id,
					t1.message_id,
					t1.history_id,
					t1.internal_date,
					t1.label_ids,
					t1.thread_id,
					t1.snippet,
					t1.from_name,
					t1.from_mail,
					t1.to_mail,
					t1.subject,
					t1.date,
					t1.body_text,
					t1.body_html,
					t1.user_id,
					t1.customer_id,
					t1.customer_exist_keyword,
					t1.lead_id,
					t1.is_read,
					t1.created_at,
					t1.updated_at,
					GROUP_CONCAT(t2.file_name) AS file_name,
					GROUP_CONCAT(t2.file_full_path) AS file_full_path,
					COUNT(DISTINCT t1.id) AS mail_count
				FROM gmail_data AS t1 
				LEFT JOIN  gmail_attachment AS t2 ON t1.id=t2.gmail_data_id
				WHERE t1.is_deleted='N' $subsql GROUP BY t1.thread_id 
				ORDER BY MAX(t1.internal_date) DESC LIMIT $start,$limit";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();die();
        // return $query->result_array();
        $rows=$query->result_array();
        $result=array();
        
        foreach($rows AS $row)
        {
        	// $get_mail_start=$this->get_gmail_detail_by_message_id($row['thread_id']);
        	$get_mail_start=$this->get_first_gmail_message_by_thread_id($row['thread_id']);
        	$get_mail_end=$this->get_gmail_detail($row['max_id']);

        	$mail_start_from_email = $get_mail_start['from_mail'];
        	$mail_start_to_email = preg_replace('/(.*)<(.*)>(.*)/sm', '\2', $get_mail_start['to_mail']);

        	$mail_end_from_email = $get_mail_end['from_mail'];
        	$mail_end_to_email = preg_replace('/(.*)<(.*)>(.*)/sm', '\2', $get_mail_end['to_mail']);

        	$first_str=$get_mail_start['from_name'];
        	//$first_str=($mail_start_from_email==$mail_start_to_email)?'me':$get_mail_start['from_name'];
        	$first_str_chk=($mail_start_from_email==$mail_start_to_email)?'me':$get_mail_start['from_mail'];

        	$last_str=$get_mail_end['from_name'];
        	//$last_str=($mail_end_from_email==$mail_end_to_email)?'me':$get_mail_end['from_name'];   
        	$last_str_chk=($mail_end_from_email==$mail_end_to_email)?'me':$get_mail_end['from_mail'];       	

        	
        	if($row['mail_count']==1){
        		$from=$first_str;
        	}
        	else if($row['mail_count']==2){
        		if($first_str_chk!=$last_str_chk){
        			$from=$first_str.','.$last_str;
        		}
        		else{
        			$from=$first_str.'...';
        		}
        		//$from=$first_str.','.$last_str;
        	}
        	else{
        		if($first_str_chk!=$last_str_chk){
        			$from=$first_str.',...'.$last_str;
        		}
        		else{
        			$from=$first_str.'...';
        		}
        		//$from=$first_str.',...'.$last_str;
        		
        	}
        	
        	//$from=$first_str;
        	
        	$email_date=date('Y-m-d H:i:s',$get_mail_end['internal_date']/1000);

        	// --------------------------------------
        	$arg=array();
			$arg['user_id']=$argument['user_id'];
			$arg['customer_exist_keyword']="";
			$arg['thread_id']=$row['thread_id'];
			$get_all_thread_mails=$this->get_all_mails_from_gmail_by_thread_id($arg);
			$emails=$get_all_thread_mails['mails'];		
			$contact_list=$this->customer_model->get_customers_by_emails($emails);						
			$contact_email_arr=array();
			$non_contact_email_arr=array();
			if(count($contact_list)>0)
			{
				foreach($contact_list AS $cl)
				{
					array_push($contact_email_arr, $cl['email']);
				}
			}			
			$contact_email_arr=array_unique($contact_email_arr);		
			//$non_contact_email_arr = array_values(array_filter(array_diff(array_merge($emails, $contact_email_arr), array_intersect($emails, $contact_email_arr))));	
			if(count($contact_email_arr)>0)
			{
				$is_contact_available='Y';
			}
			else
			{
				$is_contact_available='N';
			}
        	// ---------------------------------------
        	$result[]=array(
        					'id'=>$row['id'],
        					'subject'=>$get_mail_start['subject'],
        					'date'=>$email_date,
        					'body_text'=>$get_mail_end['body_text'],
        					'is_read'=>$row['is_read'],
        					'thread_id'=>$row['thread_id'],
        					'from_name'=>$row['from_name'],
        					'from_name_with_logic'=>$from,
        					'mail_count'=>$row['mail_count'],
        					'file_name'=>$row['file_name'],
        					'file_full_path'=>$row['file_full_path'],
        					'is_contact_available'=>$is_contact_available
        					);
        }

        return $result;
	}

	function get_gmail_response_count($argument=NULL)
    {
        $subsql = ''; 
		$subsqlInner='';
		
        // ---------------------------------------
        // SEARCH VALUE 
		
		if($argument['user_id']!='')
		{
			$subsql.=" AND t1.user_id='".$argument['user_id']."'";
		}

		$subsql.=" AND t1.lead_id>0";	
		$subsql.=" AND t1.is_read='N'";
		
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT t1.id
				FROM gmail_data AS t1
				WHERE t1.is_deleted='N'  $subsql GROUP BY t1.thread_id";

        $query = $this->client_db->query($sql,false);     
        if($query->num_rows() > 0) {
            return $query->num_rows();
        }
        else {
            return 0;
        }
    }
	public function get_gmail_ids_from_thread_id($tid)
	{
		$sql="SELECT GROUP_CONCAT(id) AS ids FROM gmail_data WHERE thread_id='".$tid."' GROUP BY thread_id";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->row()->ids;

		if($query->num_rows()>0) 
		{
			return $query->row()->ids;            
        }
        else 
        {
            return 0;
        }
	}
	public function get_gmail_detail_by_thread_id($tid)
	{
		$sql="SELECT t1.id,
					t1.message_id,
					t1.history_id,
					t1.internal_date,
					t1.label_ids,
					t1.thread_id,
					t1.snippet,
					t1.from_name,
					t1.from_mail,
					t1.to_name,
					t1.to_mail,
					t1.subject,
					t1.date,
					t1.body_text,
					t1.body_html,
					t1.user_id,
					t1.customer_id,
					t1.customer_exist_keyword,
					t1.lead_id,
					t1.is_read,
					t1.created_at,
					t1.updated_at,
					GROUP_CONCAT(t2.file_name) AS file_name,
					GROUP_CONCAT(t2.file_full_path) AS file_full_path,
					COUNT(t1.thread_id) AS mail_count
				FROM gmail_data AS t1 
				LEFT JOIN  gmail_attachment AS t2 ON t1.id=t2.gmail_data_id
				WHERE t1.is_deleted='N' AND t1.thread_id='".$tid."' GROUP BY t1.id ORDER BY t1.internal_date";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function get_last_gmail_message_by_thread_id($tid)
	{
		$sql="SELECT t1.id,
					t1.message_id,
					t1.history_id,
					t1.internal_date,
					t1.label_ids,
					t1.thread_id,
					t1.snippet,
					t1.from_name,
					t1.from_mail,
					t1.to_name,
					t1.to_mail,
					t1.subject,
					t1.date,
					t1.body_text,
					t1.body_html,
					t1.user_id,
					t1.customer_id,
					t1.customer_exist_keyword,
					t1.lead_id,
					t1.is_read,
					t1.created_at,
					t1.updated_at,
					GROUP_CONCAT(t2.file_name) AS file_name,
					GROUP_CONCAT(t2.file_full_path) AS file_full_path,
					COUNT(t1.thread_id) AS mail_count
				FROM gmail_data AS t1 
				LEFT JOIN  gmail_attachment AS t2 ON t1.id=t2.gmail_data_id
				WHERE t1.is_deleted='N' AND t1.thread_id='".$tid."' GROUP BY t1.id ORDER BY t1.id DESC LIMIT 0,1";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->row_array();

		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	public function get_first_gmail_message_by_thread_id($tid)
	{
		$sql="SELECT t1.id,
					t1.message_id,
					t1.history_id,
					t1.internal_date,
					t1.label_ids,
					t1.thread_id,
					t1.snippet,
					t1.from_name,
					t1.from_mail,
					t1.to_name,
					t1.to_mail,
					t1.subject,
					t1.date,
					t1.body_text,
					t1.body_html,
					t1.user_id,
					t1.customer_id,
					t1.customer_exist_keyword,
					t1.lead_id,
					t1.is_read,
					t1.created_at,
					t1.updated_at,
					GROUP_CONCAT(t2.file_name) AS file_name,
					GROUP_CONCAT(t2.file_full_path) AS file_full_path,
					COUNT(t1.thread_id) AS mail_count
				FROM gmail_data AS t1 
				LEFT JOIN  gmail_attachment AS t2 ON t1.id=t2.gmail_data_id
				WHERE t1.is_deleted='N' AND t1.thread_id='".$tid."' GROUP BY t1.id ORDER BY t1.id ASC LIMIT 0,1";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->row_array();

		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	public function get_existing_lead_by_thread($tid)
	{
		$sql="SELECT t1.lead_id	
			FROM gmail_data AS t1 
			WHERE t1.is_deleted='N' AND t1.thread_id='".$tid."' 
			ORDER BY t1.internal_date ASC LIMIT 0,1";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        if($query->num_rows()>0) 
		{
			return $query->row()->lead_id;            
        }
        else 
        {
            return 0;
        }        
	}

	public function get_gmail_detail_by_message_id($mid)
	{
		$sql="SELECT t1.id,
					t1.message_id,
					t1.history_id,
					t1.internal_date,
					t1.label_ids,
					t1.thread_id,
					t1.snippet,
					t1.from_name,
					t1.from_mail,
					t1.to_mail,
					t1.subject,
					t1.date,
					t1.body_text,
					t1.body_html,
					t1.user_id,
					t1.customer_id,
					t1.customer_exist_keyword,
					t1.lead_id,
					t1.is_read,
					t1.created_at,
					t1.updated_at,
					GROUP_CONCAT(t2.file_name) AS file_name,
					GROUP_CONCAT(t2.file_full_path) AS file_full_path,
					COUNT(t1.thread_id) AS mail_count
				FROM gmail_data AS t1 
				LEFT JOIN  gmail_attachment AS t2 ON t1.id=t2.gmail_data_id
				WHERE t1.is_deleted='N' AND t1.message_id='".$mid."' GROUP BY t1.message_id";
		$query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();
        //return $query->row_array();

		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	public function get_gmail_detail($id)
	{
		$sql="SELECT t1.id,
					t1.message_id,
					t1.history_id,
					t1.internal_date,
					t1.label_ids,
					t1.thread_id,
					t1.snippet,
					t1.from_name,
					t1.from_mail,
					t1.to_mail,
					t1.subject,
					t1.date,
					t1.body_text,
					t1.body_html,
					t1.user_id,
					t1.customer_id,
					t1.customer_exist_keyword,
					t1.lead_id,
					t1.is_read,
					t1.created_at,
					t1.updated_at,
					GROUP_CONCAT(t2.file_name) AS file_name,
					GROUP_CONCAT(t2.file_full_path) AS file_full_path,
					COUNT(t1.thread_id) AS mail_count
				FROM gmail_data AS t1 
				LEFT JOIN  gmail_attachment AS t2 ON t1.id=t2.gmail_data_id
				WHERE t1.is_deleted='N' AND t1.id='".$id."' GROUP BY t1.id";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->row_array();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	function gmail_data_update_by_thread_id($data,$tid)
	{
		$this->client_db->where('thread_id',$tid);
		if($this->client_db->update('gmail_data',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function gmail_data_delete_by_thread_id($tid)
	{
		$this->client_db -> where('thread_id', $tid);
		$this->client_db -> delete('gmail_data');
	}

	public function get_attachments_by_gmail_ids($ids)
	{
		$sql="SELECT GROUP_CONCAT(file_name) AS file_name 
		FROM gmail_attachment WHERE gmail_data_id IN (".$ids.") GROUP BY gmail_data_id";
		$query = $this->client_db->query($sql,false);       
		//return $query->row_array();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}

	}

	public function get_all_mails_from_gmail_by_thread_id($argument=null)
	{
		// -----------------------------------------------
		// SEARCH
		$subsql='';
		if($argument['user_id']!='')
		{
			$subsql.=" AND t1.user_id='".$argument['user_id']."'";
		}

		if($argument['customer_exist_keyword']!='')
		{
			//$subsql.=" AND t1.customer_exist_keyword IN (".$argument['customer_exist_keyword'].")";
		}		
		if($argument['thread_id']!='')
		{
			$subsql.=" AND t1.thread_id='".$argument['thread_id']."'";
		}
		// SEARCH
		// -----------------------------------------------		

		$sql="SELECT 
				GROUP_CONCAT(t1.from_mail) AS from_mail, 
				GROUP_CONCAT(t1.to_mail) AS to_mails
				FROM gmail_data AS t1
				WHERE 1=1 $subsql GROUP BY t1.thread_id";
		$query = $this->client_db->query($sql,false);       
		$row=$query->row_array();
		$mails=[];
		$from_mails=[];
		$to_mails=[];
		$forms_arr=explode(",", $row['from_mail']);
		foreach($forms_arr AS $form)
		{
			//$tmp_mail=preg_replace('/(.*)<(.*)>(.*)/sm', '\2', $form);
			array_push($mails, $form);
			array_push($from_mails, $form);
		}

		$tos_arr=explode(",", $row['to_mails']);
		foreach($tos_arr AS $to)
		{
			//$tmp_mail=preg_replace('/(.*)<(.*)>(.*)/sm', '\2', $to);
			array_push($mails, $to);
			array_push($to_mails, $to);
		}
		return array(
					'from_mails'=>array_unique($from_mails),
					'to_mails'=>array_unique($to_mails),
					'mails'=>array_unique($mails)
					);
	}
	// GMAIL API
	// ---------------------------------------------


	public function get_call_log_detail($id,$client_info=array())
	{	
		if($this->class_name=='rest_lead')
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
		$sql="SELECT id,
					lead_id,
					user_id,
					company_id,
					contact_person,
					contact_number,
					company_name,
					call_status,
					exact_call_start,
					exact_call_end,
					msg,
					created_at,
					updated_at 
		FROM lead_wise_call_request_log  
		WHERE id='".$id."'";
		$query = $this->client_db->query($sql,false);   
		//return $query->row_array();  
		
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	function CreateCallScheduleLog($data,$client_info=array())
	{
		if($this->class_name=='rest_lead')
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
		if($this->client_db->insert('lead_wise_call_request_log',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function get_call_log($argument=array(),$client_info=array())
	{
		if($this->class_name=='rest_lead')
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

		$subsql="";
		$limit=$argument['limit'];
      	$start=$argument['start'];
		// ----------------------------------------
		// SEARCH
		if(isset($argument['lead_id']))
		{
			if($argument['lead_id']){
				$subsql .=" AND t1.lead_id='".$argument['lead_id']."'";
			}
		}
		if(isset($argument['user_id']))
		{
			if($argument['user_id']){
				$subsql .=" AND t1.user_id='".$argument['user_id']."'";
			}
		}
		if(isset($argument['call_status']))
		{
			if($argument['call_status']!='A'){

				if(strtoupper($argument['call_status'])=='Y')
				{
					$call_status_str="'Y'";
				}
				else if(strtoupper($argument['call_status'])=='N')
				{
					$call_status_str="'N'";
				}
				else if(strtoupper($argument['call_status'])=='D')
				{
					$call_status_str="'D'";
				}
				else if(strtoupper($argument['call_status'])=='O')
				{
					$call_status_str="'O'";
				}
				else if(strtoupper($argument['call_status'])=='ND' || strtoupper($argument['call_status'])=='DN')
				{
					$call_status_str="'N','D'";
				}
				else if(strtoupper($argument['call_status'])=='YD' || strtoupper($argument['call_status'])=='DY')
				{
					$call_status_str="'Y','D'";
				}
				else if(strtoupper($argument['call_status'])=='YDF')
				{
					$call_status_str="'Y','D','F'";
				}
				else if(strtoupper($argument['call_status'])=='NO')
				{
					$call_status_str="'N','O'";
				}
				$subsql .=" AND t1.call_status IN (".$call_status_str.")";
			}
		}
		// SEARCH
		// ----------------------------------------
		
		$sql="SELECT id,
					lead_id,
					user_id,
					company_id,
					contact_person,
					contact_number,
					company_name,
					call_status,
					call_medium,
					exact_call_start,
					exact_call_end,
					msg,
					next_followup_date,
					created_at,
					updated_at 
		FROM lead_wise_call_request_log AS t1 
		WHERE 1=1 $subsql ORDER BY updated_at DESC LIMIT $start,$limit";
		$query = $this->client_db->query($sql,false);   
		if($query){
			if($query->num_rows() == 0) 
			{
				return $query->row_array();            
			}
			else 
			{
				return $query->result_array();
			}
		}
		else{
			return array();
		}
		
	}

	function UpdateCallScheduleLog($data,$id,$client_info=array())
	{
		if($this->class_name=='rest_lead')
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
		if($this->client_db->update('lead_wise_call_request_log',$data))
		{
			// return $last_query = $this->client_db->last_query();
			return true;
		}
		else
		{
			return false;
		}
	}

	public function DeleteCallScheduleLog($id,$client_info=array())
	{
		if($this->class_name=='rest_lead')
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
		$this->client_db->where('id', $id);
		$this->client_db->delete('lead_wise_call_request_log');
	}

	function get_decision_for_gmail($arg=array())
	{
		$email=$arg['email'];				
		if($email!='')
		{
			$sql="SELECT id FROM customer WHERE status='1' AND email='".$email."";
			$query=$this->client_db->query($sql);		
			if($query->num_rows()==0)
			{			
				return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
			}
			else if($query->num_rows()==1)
			{
				$row=$query->row();
				return array('msg'=>'one_customer_exist','customer_id'=>$row->id,'status'=>TRUE);	
			}	
			else if($query->num_rows()>1)
			{
				$row=$query->row();
				return array('msg'=>'multiple_customer_exist','customer_id'=>$row->id,'status'=>TRUE);	
			}
		}
		else
		{
			return array('msg'=>'SENDEREMAIL and MOB both missing','customer_id'=>'','status'=>FALSE);
		}					
	}


	public function get_lead_id_from_customers($cust_id,$filter_by_stage)
	{
		$subsql='';
		if($filter_by_stage=='pending_lead')
		{
			$subsql .=" AND current_stage_id IN ('1','2')";
		}
		else if($filter_by_stage=='closed_lead')
		{
			$subsql .=" AND current_stage_id NOT IN ('1','2','4')";
		}

		
		$sql="SELECT id
		FROM  lead 
		WHERE customer_id='".$cust_id."' AND status='1' $subsql ORDER BY id LIMIT 0,1";
		
		
		$query = $this->client_db->query($sql,false);   
		if($query->num_rows()>0) 
		{
			return $query->row()->id;            
        }
        else 
        {
            return 0;
        }
	}

	public function get_all_lead_id_from_customers($cust_id,$filter_by_stage)
	{
		$subsql='';
		if($filter_by_stage=='pending_lead')
		{
			$subsql .=" AND current_stage_id IN ('1','2')";
		}
		else if($filter_by_stage=='closed_lead')
		{
			$subsql .=" AND current_stage_id NOT IN ('1','2','4')";
		}
		
			
		$sql="SELECT id
		FROM  lead 
		WHERE customer_id='".$cust_id."' AND status='1' $subsql ORDER BY id ASC";
		
		
		$query = $this->client_db->query($sql,false);  
		$arr=[]; 
		if($query->num_rows()>0) 
		{
			foreach ($query->result() as $row) 
			{ 
				array_push($arr,$row->id);				
			}
			return $arr;            
        }
        else 
        {
            return 0;
        }
	}

	public function get_next_follow_by()
	{
		$sql="SELECT id,name
		FROM  next_followup_type ORDER BY name";		
		$query = $this->client_db->query($sql,false);   
		//return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function get_webwhatsapp_template_list($user_id='',$client_info=array())
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

		$subsql='';
		if($user_id)
		{
			$subsql .=" AND user_id IN ('0','".$user_id."')";
		}
		$sql="SELECT id,name,description,user_id
		FROM web_whatsapp_templates WHERE 1=1 $subsql ORDER BY user_id,name";
		$query = $this->client_db->query($sql,false);   
		//return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}
	public function get_webwhatsapp_template($id='',$client_info=array())
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
		$sql="SELECT id,name,description,user_id
		FROM web_whatsapp_templates WHERE id='".$id."'";
		$query = $this->client_db->query($sql,false);   
		//return $query->row_array();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	public function add_web_whatsapp_template($data)
	{
		if($this->client_db->insert('web_whatsapp_templates',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function is_accept_call_request($user_id,$lead_id)
	{		
		$sql="SELECT id FROM lead_wise_call_request_log 
		WHERE lead_id='".$lead_id."' AND user_id='".$user_id."' AND call_status IN ('N','O')";
		$query = $this->client_db->query($sql,false); 
		if($query){
			if($query->num_rows()>0) 
			{
				return'N';           
			}
			else 
			{
				return'Y';
			}
		}
		else{
			return'';
		}
		
	}

	public function delete_web_whatsapp_template($id)
	{
		$this->client_db -> where('id', $id);
    	$this->client_db -> delete('web_whatsapp_templates');
	}

	public function get_prev_thread($thread_list,$curr_thrad_id)
	{
		if(in_array($curr_thrad_id, $thread_list))
		{
			$key=array_search($curr_thrad_id,$thread_list);
			$prev_key=($key-1);
			if(isset($thread_list[$prev_key]))
			{
				return $thread_list[$prev_key];
			}
			else
			{
				return '';
			}
		}
		else
		{
			return '';
		}
	}

	public function get_next_thread($thread_list,$curr_thrad_id)
	{
		if(in_array($curr_thrad_id, $thread_list))
		{
			$key=array_search($curr_thrad_id,$thread_list);
			$prev_key=($key+1);
			if(isset($thread_list[$prev_key]))
			{
				return $thread_list[$prev_key];
			}
			else
			{
				return '';
			}
		}
		else
		{
			return '';
		}
	}

	public function gmail_data_mail_add($data)
	{
		if($this->client_db->insert('gmail_data_mails',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function gmail_data_mail_delete_by_thread_id($tid)
	{
		$this ->client_db->where('thread_id', $tid);
		$this ->client_db->delete('gmail_data_mails');
	}

	function CreateLeadWiseProductTag($data,$client_info=array())
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
		if($this->client_db->insert('lead_wise_product_service',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	function get_lead_wise_stage_log($lead_id,$client_info=array())
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
        $subsql = '';   
        $subsql .= " AND lead.id='".$lead_id."'";            
		$sql="SELECT 			
			lead.id,
			GROUP_CONCAT(stage_log.stage_id ORDER BY stage_log.id) AS stage_logs	
			FROM lead 
			LEFT JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id 
			WHERE lead.status='1' $subsql GROUP BY lead.id";
		// echo $sql;die();
        $query = $this->client_db->query($sql,false);        
        $last_query = $this->client_db->last_query();
        //return $query->row();

		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
    }

    function is_stage_exist_in_log($lead_id,$stage_id,$client_info=array())
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
		
        $subsql = ''; 
		$sql="SELECT id FROM lead_stage_log 
		WHERE lead_id='".$lead_id."' AND stage_id='".$stage_id."'";
		// echo $sql;die();
        $query = $this->client_db->query($sql,false);        
        // $last_query = $this->client_db->last_query();
        // return $query->row();
		if($query){
			if($query->num_rows()>0)
			{
				return 'Y';
			}
			else
			{
				return 'N';
			}
		}
		else{
			return 'N';
		}
        
    }

    // ==========================================================
    // LEAD WISE PRODUCT/SERVICES TAGGED
    function tagged_ps_count($lead_id)
    {
    	$sql="SELECT id FROM lead_wise_product_service 
		WHERE lead_id='".$lead_id."'";
		$query = $this->client_db->query($sql,false); 
		//return $query->num_rows();
		if($query->num_rows()>0) 
		{
			return $query->num_rows();         
		}
		else 
		{
			return 0;
		}
    }
    function delete_tagged_ps($id)
    {
    	$this ->client_db->where('id', $id);
		$this ->client_db->delete('lead_wise_product_service');
    }
    function delete_tagged_ps_by_leadid($lead_id)
    {
    	$this ->client_db->where('lead_id', $lead_id);
		$this ->client_db->delete('lead_wise_product_service');
    }
    function get_tagged_ps($lead_id)
    {
    	$sql="SELECT id,lead_id,name,product_id,tag_type FROM lead_wise_product_service 
		WHERE lead_id='".$lead_id."'";
		$query = $this->client_db->query($sql,false); 
		//return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
    }
	function list_tagged_product_name()
    {
    	$sql="SELECT id,name FROM lead_wise_product_service GROUP BY name ORDER BY name";
		$query = $this->client_db->query($sql,false); 
		//return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
    }

	function is_exist_tagged_ps_by_leadid($lead_id,$pid='',$tag_type='L')
    {
    	$sql="SELECT id FROM lead_wise_product_service 
		WHERE lead_id='".$lead_id."' AND product_id='".$pid."' AND tag_type='".$tag_type."'";
		$query = $this->client_db->query($sql,false); 
		if($query){
			if($query->num_rows()==0)
			{
				return 'N';
			}
			else
			{
				return 'Y';
			}
		}
		else{
			return 'N';
		}
		
    }

	function get_tagged_ps_list($lead_id,$tag_type='L',$client_info=array())
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

		$subsql="";		
		if($tag_type)
		{
			$subsql .=" AND tag_type='".$tag_type."'";
		}
    	$sql="SELECT product_id,name FROM lead_wise_product_service 
		WHERE lead_id='".$lead_id."'  $subsql";
		$query = $this->client_db->query($sql,false); 
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
		
    }

    // LEAD WISE PRODUCT/SERVICES TAGGED
    // ==========================================================

    function CreateC2CLog($data)
	{
		if($this->client_db->insert('lead_wise_c2c_log',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function UpdateC2CLog($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('lead_wise_c2c_log',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function priority_wise_stage_key_val()
	{
		$return=array();
		$all=array();

		$sql="SELECT id,name,sort,is_system_generated,is_active_lead FROM opportunity_stage 
		ORDER BY sort";
		$query = $this->client_db->query($sql,false); 
		$rows=$query->result();
		if($query->num_rows()>0)
		{
			foreach($rows AS $row)
	    	{
	    		$all[$row->sort]=$row->id;
	    	}
		}
		return $all;   	
	}

	function priority_wise_stage($client_info=array())
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

		$return=array();
		$all=array();
		$system_generated_stages_y=array();
		$system_generated_stages_n=array();
		$active_lead_stages_y=array();
		$active_lead_stages_n=array();

		$sql="SELECT id,name,sort,is_system_generated,is_active_lead FROM opportunity_stage WHERE is_deleted='N' 
			ORDER BY sort";
		$query = $this->client_db->query($sql,false); 
		
		if($query){
			$rows=$query->result();
			if($query->num_rows()>0)
			{
				foreach($rows AS $row)
				{
					$all[]=array(
								'id'=>$row->id,
								'name'=>$row->name,
								'sort'=>$row->sort,
								);

					if($row->is_system_generated=='Y'){
						$system_generated_stages_y[]=array(
								'id'=>$row->id,
								'name'=>$row->name,
								'sort'=>$row->sort,
								);
					}

					if($row->is_system_generated=='N'){
						$system_generated_stages_n[]=array(
								'id'=>$row->id,
								'name'=>$row->name,
								'sort'=>$row->sort,
								);
					}

					if($row->is_active_lead=='Y'){
						$active_lead_stages_y[]=array(
								'id'=>$row->id,
								'name'=>$row->name,
								'sort'=>$row->sort,
								);
					}

					if($row->is_active_lead=='N'){
						$active_lead_stages_n[]=array(
								'id'=>$row->id,
								'name'=>$row->name,
								'sort'=>$row->sort,
								);
					}
				}
			}
		}
		
		return $return=array(
							'all'=>$all,
							'system_generated_stages_y'=>$system_generated_stages_y,
							'system_generated_stages_n'=>$system_generated_stages_n,
							'active_lead_stages_y'=>$active_lead_stages_y,
							'active_lead_stages_n'=>$active_lead_stages_n
							);    	
	}

	function AddCallHistoryForLead($data,$client_info=array())
	{
		if($this->class_name=='rest_lead')
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
		if($this->client_db->insert('tbl_call_history_for_lead_tmp',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function DuplicateChkCallHistoryForLead($data,$client_info=array())
	{
		if($this->class_name=='rest_lead')
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

		$number=$data['number'];
		$call_start=$data['call_start'];
		$call_end=$data['call_end'];
		// $bound_type=$data['bound_type'];

		$sql="SELECT id FROM tbl_call_history_for_lead_tmp 
		WHERE number='".$number."' AND call_start='".$call_start."' AND call_end='".$call_end."'";
		$query = $this->client_db->query($sql,false); 	
		if($query){
			if($query->num_rows()>0){
				return false;
			}
			else{
				return true;
			}
		}
		else{
			return true;
		}	
				
	}

	// =====================================
	// CALL LOG LEADS
	function get_sync_call_list_count($argument=NULL)
    {
        $subsql = ''; 
		$subsqlInner='';
        // ---------------------------------------
        // SEARCH VALUE 
        if($argument['assigned_user']!='')
		{
			$subsql.=" AND t1.user_id IN (".$argument['assigned_user'].")";
		}
		
        if($argument['filter_sync_call_filter_by_keyword']!='')
        {
        	$subsql .= " AND (t1.name LIKE '%".$argument['filter_sync_call_filter_by_keyword']."%' || t1.number LIKE '%".$argument['filter_sync_call_filter_by_keyword']."%') "; 
        }

        if($argument['filter_sync_call_from_date']!='' && $argument['filter_sync_call_to_date']!='')
		{			
			
			$from = date_display_format_to_db_format($argument['filter_sync_call_from_date']);  
			$to = date_display_format_to_db_format($argument['filter_sync_call_to_date']);  
			$subsql.=" AND (DATE(t1.call_start) BETWEEN '".$from."' AND '".$to."') ";
		}
        
        if($argument['filter_sync_call_call_type']!='')
        {
        	$subsql .= " AND LOWER(t1.bound_type)='".strtolower($argument['filter_sync_call_call_type'])."'"; 
        }

        if($argument['filter_sync_call_buyer_type']!='')
        {     	

        	if($argument['filter_sync_call_buyer_type']=='existing_buyer')
        	{
        		$subsqlInner .=' INNER JOIN customer AS cust ON t1.number=cust.mobile';
        		$subsql .= "";
        	}
        	else if($argument['filter_sync_call_buyer_type']=='new_buyer')
        	{
        		$subsqlInner .=' LEFT JOIN customer AS cust ON t1.number=cust.mobile';
        		$subsql .= " AND cust.mobile IS NULL";
        	}        	
        }
        else
        {
        	$subsqlInner .=' LEFT JOIN customer AS cust ON t1.number=cust.mobile';
        }
		
        // SEARCH VALUE
        // ---------------------------------------

        $sql="SELECT t1.id,
			t1.name,
			t1.country_code,
			t1.number,
			t1.call_start,
			t1.call_end,
			t1.bound_type,
			t1.agent_mobile,
			t1.created_at 
			FROM tbl_call_history_for_lead_tmp AS t1 $subsqlInner
			WHERE t1.status='0' AND t1.is_deleted='N' $subsql"; 
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
    
    function get_sync_call_list($argument=NULL)
    {       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];

		if(trim($start)!=''){
			$limitcond="LIMIT ".$start.",".$limit;
		} else {
			$limitcond="";
		}	


        $subsql = '';   
		$subsqlInner='';
        $order_by_str = " ORDER BY t1.call_start DESC ";
        if($argument['filter_sort_by']!='')
        {
			/*
        	if($argument['filter_sort_by']=='P_L_M')
            	$order_by_str = " ORDER BY  t1.date_modified DESC ";
            else if($argument['filter_sort_by']=='P_H_TO_L')
            	$order_by_str = " ORDER BY  t1.price DESC";
            else if($argument['filter_sort_by']=='P_L_TO_H')
            	$order_by_str = " ORDER BY  t1.price ASC";
			*/
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 
        if($argument['assigned_user']!='')
		{
			$subsql.=" AND t1.user_id IN (".$argument['assigned_user'].")";
		}

        if($argument['filter_sync_call_filter_by_keyword']!='')
        {
        	$subsql .= " AND (t1.name LIKE '%".$argument['filter_sync_call_filter_by_keyword']."%' || t1.number LIKE '%".$argument['filter_sync_call_filter_by_keyword']."%') "; 
        }

        if($argument['filter_sync_call_from_date']!='' && $argument['filter_sync_call_to_date']!='')
		{			
			
			$from = date_display_format_to_db_format($argument['filter_sync_call_from_date']);  
			$to = date_display_format_to_db_format($argument['filter_sync_call_to_date']);  
			$subsql.=" AND (DATE(t1.call_start) BETWEEN '".$from."' AND '".$to."') ";
		}
        
        if($argument['filter_sync_call_call_type']!='')
        {
        	$subsql .= " AND LOWER(t1.bound_type)='".strtolower($argument['filter_sync_call_call_type'])."'"; 
        }

        if($argument['filter_sync_call_buyer_type']!='')
        {     	
        	$subsql .= " AND t1.number!=''";
        	if($argument['filter_sync_call_buyer_type']=='existing_buyer')
        	{
        		$subsqlInner .=' INNER JOIN customer AS cust ON t1.number=cust.mobile';       		
        		
        	}
        	else if($argument['filter_sync_call_buyer_type']=='new_buyer')
        	{
        		$subsqlInner .=' LEFT JOIN customer AS cust ON t1.number=cust.mobile';
        		$subsql .= " AND cust.mobile IS NULL";
        	}        	
        }
        else
        {
        	$subsqlInner .=' LEFT JOIN customer AS cust ON t1.number=cust.mobile';
        }

        $subsqlInner .=" LEFT JOIN 
					(
						SELECT 
						lead.customer_id AS custid,
						IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer,
						GROUP_CONCAT( DISTINCT lead.id SEPARATOR ',') AS lead_str,
						GROUP_CONCAT( DISTINCT concat(lead.id,'#',lead.current_stage_id) ORDER BY lead.id SEPARATOR ',' ) AS lead_stage_str
						FROM lead 
						LEFT JOIN lead_stage_log AS s_log 
						ON s_log.lead_id=lead.id 
						GROUP BY lead.customer_id
					) AS c_paying_info 
					ON c_paying_info.custid=cust.id";
		
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id,
			t1.name,
			t1.country_code,
			t1.number,
			t1.call_start,
			t1.call_end,
			TIMESTAMPDIFF(SECOND, t1.call_start, t1.call_end) AS call_time_in_second,
			t1.bound_type,
			t1.agent_mobile,
			t1.status,
			t1.status_wise_msg,
			t1.created_at,
			cust.mobile AS cust_mobile,
			cust.company_name AS cust_company_name,
			GROUP_CONCAT(cust.id) AS cust_id_str,
			t2.name AS assigned_user_name,
			c_paying_info.*
			FROM tbl_call_history_for_lead_tmp AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id 
			$subsqlInner 			
			WHERE t1.status='0' AND t1.is_deleted='N' $subsql GROUP BY t1.id $order_by_str 
			$limitcond"; 
        $query = $this->client_db->query($sql,false);
        // return $query->result();		
		$arr = array();
		if($query){
			if($query->num_rows() > 0)
			{    
				foreach($query->result() as $row)
				{
					$deal_won_lead_arr=array();
					$in_active_lead_arr=array();
					$active_lead_arr=array();
					$inactive_lead_count=0;
					$is_active_stage_available='N';
					$lead_stage_arr_tmp=explode(',',$row->lead_stage_str);
					if(count($lead_stage_arr_tmp)){
						$in_active_stages_arr=array('3','4','5','6','7');
						foreach($lead_stage_arr_tmp AS $lead_stage){
							$lead_stage_arr=explode("#",$lead_stage);
							$l_id=$lead_stage_arr[0];
							$s_id=$lead_stage_arr[1];
							if(in_array($s_id,$in_active_stages_arr)){
								$inactive_lead_count++;
								array_push($in_active_lead_arr,$l_id);
								if($s_id=='4'){
									array_push($deal_won_lead_arr,$l_id);
								}
							}
							else{
								
								array_push($active_lead_arr,$l_id);
							}
						}
					}
					if($inactive_lead_count==count($lead_stage_arr_tmp)){
						$is_active_stage_available='N';
					}
					else{
						$is_active_stage_available='Y';
					}

					
					if($row->lead_str){
						if($is_active_stage_available=='N'){
							$last_lead=end($in_active_lead_arr);
						}
						else if($is_active_stage_available=='Y'){						
							if(count($deal_won_lead_arr)>0){
								$last_lead=end($deal_won_lead_arr);
							}
							else{
								$last_lead=end($active_lead_arr);
							}						
						}
						else{
							$lead_arr=explode(",",$row->lead_str);
							$last_lead=end($lead_arr);
						}
						
						$current_stage_id=get_value("current_stage_id","lead","id=".$last_lead);
						$assigned_user_id=get_value("assigned_user_id","lead","id=".$last_lead);

						$sql_s_log="SELECT GROUP_CONCAT( DISTINCT stage_id SEPARATOR ',') AS stage_id_log_str 
									FROM lead_stage_log WHERE lead_id='".$last_lead."' GROUP BY lead_id ORDER BY id";
						$query_s_log=$this->client_db->query($sql_s_log);
						$row_s_log=$query_s_log->row();
						$stage_id_log_str=$row_s_log->stage_id_log_str;
					}
					else{
						$last_lead='';
						$stage_id_log_str='';
					}
					$arr[]=(object)array(
								'id'=>$row->id,
								'name'=>$row->name,
								'country_code'=>$row->country_code,
								'number'=>$row->number,
								'call_start'=>$row->call_start,
								'call_end'=>$row->call_end,
								'call_time_in_second'=>$row->call_time_in_second,
								'bound_type'=>$row->bound_type,
								'agent_mobile'=>$row->agent_mobile,
								'status'=>$row->status,
								'status_wise_msg'=>$row->status_wise_msg,
								'created_at'=>$row->created_at,
								'cust_mobile'=>$row->cust_mobile,
								'cust_company_name'=>$row->cust_company_name,
								'cust_id_str'=>$row->cust_id_str,
								'assigned_user_name'=>$row->assigned_user_name,
								'is_paying_customer'=>$row->is_paying_customer,
								'stage_id_log_str'=>$stage_id_log_str,
								'lead_str'=>$row->lead_str,
								'lead_stage_str'=>$row->lead_stage_str,
								'current_stage_id'=>$current_stage_id,
								'last_lead'=>$last_lead,
								'assigned_user_id'=>$assigned_user_id,
								'is_active_stage_available'=>$is_active_stage_available,
								'active_lead_arr'=>$active_lead_arr,
								'in_active_lead_arr'=>$in_active_lead_arr,
								'deal_won_lead_arr'=>$deal_won_lead_arr,
							);
				}
			}
		}
		
		return $arr;
    }

    function update_sync_call($data,$id)
	{		
		$this->client_db->where('id',$id);
		if($this->client_db->update('tbl_call_history_for_lead_tmp',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function add_sync_call_tagged_lead_wise($data)
	{		
		if($this->client_db->insert('tbl_call_history_for_lead_tmp_tagged_lead',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function get_sync_call_row($id)
    {            
		$sql="SELECT t1.id,
			t1.user_id,
			t1.name,
			t1.country_code,
			t1.number,
			t1.call_start,
			t1.call_end,
			t1.bound_type,
			t1.agent_mobile,
			t1.created_at,
			cust.assigned_user_id,
			GROUP_CONCAT(cust.contact_person) AS cust_contact_person_str,
			GROUP_CONCAT(cust.company_name) AS cust_company_name_str,
			GROUP_CONCAT(cust.id) AS cust_id_str,
			GROUP_CONCAT(cust.mobile) AS cust_mobile_str,
			GROUP_CONCAT(cust.email) AS cust_email_str,
			GROUP_CONCAT(CONCAT(cust.id,'#',cust.is_blacklist)) AS is_blacklist_str
			FROM tbl_call_history_for_lead_tmp AS t1 
			LEFT JOIN customer AS cust ON t1.number=cust.mobile 
			WHERE t1.id='".$id."' GROUP BY t1.id"; 
        $query = $this->client_db->query($sql,false);
        //return $query->row();

		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
    }

	function get_sync_call_one($id)
    {            
		$sql="SELECT 
			t1.country_code,
			t1.number
			FROM tbl_call_history_for_lead_tmp AS t1 WHERE t1.id='".$id."'"; 
        $query = $this->client_db->query($sql,false);
        //return $query->row();

		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
    }

    public function delete_sync_call($id)
	{
		$this->client_db -> where('id', $id);
    	$this->client_db -> delete('tbl_call_history_for_lead_tmp');
	}

	public function call_sync_com_source_id()
	{
		$sql="SELECT id FROM source 
		WHERE replace(LOWER(name),' ','')='calllog'"; 
        $query = $this->client_db->query($sql,false);
        if($query->num_rows() > 0) {
            $row=$query->row();
            return $row->id;
        }
        else{
    		$data=array('name'=>'Call Log');
        	if($this->client_db->insert('source',$data))
	   		{		   			
	           return $this->client_db->insert_id();
	   		}
	   		else
	   		{
	          return '';
	   		}
        }        
	}

	public function call_sync_com_country_id($country_code='')
	{
		$sql="SELECT id FROM countries 
		WHERE phonecode='".$country_code."'"; 
        $query = $this->client_db->query($sql,false);
        if($query->num_rows() > 0) {
            $row=$query->row();
            return $row->id;
        }
        else{
    		return '';
        }        
	}

	function get_sync_call_report_list($argument=NULL)
    {       
        $result = array();        
        $subsql = '';   
		$subsqlInner='';
        $order_by_str = " ORDER BY t1.call_start DESC ";
        if($argument['filter_sort_by']!='')
        {
			/*
        	if($argument['filter_sort_by']=='P_L_M')
            	$order_by_str = " ORDER BY  t1.date_modified DESC ";
            else if($argument['filter_sort_by']=='P_H_TO_L')
            	$order_by_str = " ORDER BY  t1.price DESC";
            else if($argument['filter_sort_by']=='P_L_TO_H')
            	$order_by_str = " ORDER BY  t1.price ASC";
			*/
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 
        if($argument['assigned_user']!='')
		{
			$subsql.=" AND t1.user_id IN (".$argument['assigned_user'].")";
		} 

		if($argument['filter_scr_year']!='')
		{
			$subsql.=" AND DATE_FORMAT(t1.call_start,'%Y')='".$argument['filter_scr_year']."'";
		} 

		if($argument['filter_scr_month']!='')
		{
			$subsql.=" AND DATE_FORMAT(t1.call_start,'%m')='".$argument['filter_scr_month']."'";
		}   
		
		if($argument['filter_scr_from_date']!='' && $argument['filter_scr_to_date']!='')
		{
			if($argument['filter_scr_from_date']<=$argument['filter_scr_to_date']){
				$subsql.=" AND (DATE_FORMAT(t1.call_start,'%Y-%m-%d')>='".$argument['filter_scr_from_date']."' AND DATE_FORMAT(t1.call_start,'%Y-%m-%d')<='".$argument['filter_scr_to_date']."')";
			}
			else{
				$subsql.=" AND DATE_FORMAT(t1.call_start,'%Y-%m-%d')>='".date('Y-m-d')."'";
			}			
		}  
		else{
			// $subsql.=" AND DATE_FORMAT(t1.call_start,'%Y-%m-%d')>='".date('Y-m-d')."'";
		} 
		
		// $subsqlInner .=' LEFT JOIN customer AS cust ON t1.number=cust.mobile';
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.call_start,
			SUM(TIMESTAMPDIFF(SECOND, t1.call_start, t1.call_end)) AS total_talked_time_in_second,
			COUNT(t1.call_start) AS total_call_count,			
			(COUNT(DISTINCT t1.call_start)-SUM(IF(t1.status='1',1,0))) AS talked_call_count_1,
			SUM(IF(t1.status='1',0,1)) AS talked_call_count,
			SUM(IF(t1.status='1',1,0)) AS not_talked_call_count,
			COUNT(DISTINCT t1.number) AS unique_call_count,
			SUM(IF(t1.bound_type='outgoing',1,0)) AS outgoing_call_count,
			SUM(IF(t1.bound_type='incoming',1,0)) AS incoming_call_count,
			SUM(IF(t1.status='0',1,0)) AS missing_opportunities_call_count,
			SUM(IF(t1.status='2',1,0)) AS new_leads_created_call_count,
			SUM(IF(t1.status='3',1,0)) AS sales_service_call_count,
			SUM(IF(t1.status='4',1,0)) AS other_business_count,
			t1.name,
			t1.country_code,
			t1.number,
			t1.call_start,
			t1.call_end,			
			t1.bound_type,
			t1.agent_mobile,
			t1.created_at			 
			FROM tbl_call_history_for_lead_tmp AS t1 
			$subsqlInner
			WHERE t1.is_deleted='N' $subsql GROUP BY DATE_FORMAT(t1.call_start,'%Y%m%d') $order_by_str "; 
			// echo $sql; die();
        $query = $this->client_db->query($sql,false);
        //echo $last_query = $this->client_db->last_query();die();
        //return $query->result();

		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
    }

    public function get_lead_list_from_number($argument=array())
    {
    	$active_lead_stages=$argument['active_lead_stages'];
    	$number=$argument['number'];
    	$sql="SELECT 
    			t2.id,
    			t2.title 
    			FROM customer AS t1 
    			INNER JOIN lead AS t2 
    			ON t1.id=t2.customer_id 
    			WHERE t1.mobile='".$number."' AND t2.current_stage_id IN (".$active_lead_stages.")";
    	$query = $this->client_db->query($sql,false);
    	//echo $last_query = $this->client_db->last_query();die();
        //return $query->result();

		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
    }

    

    function get_call_history_report_detail_list($argument=NULL)
    {       
        $result = array();        
        $subsql = '';   
		$subsqlInner='';
		$group_by_str=" GROUP BY t3.id";
        $order_by_str = " ORDER BY t1.call_start DESC ";
        if($argument['filter_sort_by']!='')
        {
			/*
        	if($argument['filter_sort_by']=='P_L_M')
            	$order_by_str = " ORDER BY  t1.date_modified DESC ";
            else if($argument['filter_sort_by']=='P_H_TO_L')
            	$order_by_str = " ORDER BY  t1.price DESC";
            else if($argument['filter_sort_by']=='P_L_TO_H')
            	$order_by_str = " ORDER BY  t1.price ASC";
			*/
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 
		if($argument['lead_id']!='')
		{
			$subsql.=" AND t3.tagged_lead_id ='".$argument['lead_id']."'";
		} 

        if($argument['assigned_user']!='')
		{
			$subsql.=" AND t1.user_id IN (".$argument['assigned_user'].")";
		} 

		if($argument['date']!='')
        {
        	$subsql .= " AND DATE(t1.call_start)='".date_display_format_to_db_format($argument['date'])."'"; 
        }

        if($argument['type']!='')
		{
			if($argument['type']=='talked_call'){
				$subsql.=" AND t1.status!='1'";
			}
			else if($argument['type']=='not_talked_call'){
				$subsql.=" AND t1.status='1'";
			}
			else if($argument['type']=='unique_call'){
				$group_by_str=" GROUP BY t1.number";
			}
			else if($argument['type']=='outgoing_call'){
				$subsql.=" AND t1.bound_type='outgoing'";
			}
			else if($argument['type']=='incoming_call'){
				$subsql.=" AND t1.bound_type='incoming'";
			}
			else if($argument['type']=='missing_opportunities_call'){
				$subsql.=" AND t1.status='0'";
			}
			else if($argument['type']=='new_lead_call'){
				$subsql.=" AND t1.status='2'";
			}
			else if($argument['type']=='sales_service_call'){
				$subsql.=" AND t1.status='3'";
			}
			else if($argument['type']=='other_business_call'){
				$subsql.=" AND t1.status='4'";
			}
			
		}

		if($argument['filter_scr_year']!='')
		{
			$subsql.=" AND DATE_FORMAT(t1.call_start,'%Y')='".$argument['filter_scr_year']."'";
		} 

		if($argument['filter_scr_month']!='')
		{
			$subsql.=" AND DATE_FORMAT(t1.call_start,'%m')='".$argument['filter_scr_month']."'";
		}        
		
		$subsqlInner .=' LEFT JOIN customer AS cust ON t1.number=cust.mobile';

		$subsqlInner .=" LEFT JOIN 
					(
						SELECT lead.customer_id AS custid,
						IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer 
						FROM lead 
						LEFT JOIN lead_stage_log AS s_log 
						ON s_log.lead_id=lead.id 
						GROUP BY lead.customer_id
					) AS c_paying_info 
					ON c_paying_info.custid=cust.id";
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id,
			t1.call_start,
			SUM(TIMESTAMPDIFF(SECOND, t1.call_start, t1.call_end)) AS total_talked_time_in_second,
			COUNT(DISTINCT t1.call_start) AS total_call_count,
			(COUNT(DISTINCT t1.call_start)-SUM(IF(t1.status='1',1,0))) AS talked_call_count_1,
			SUM(IF(t1.status='1',0,1)) AS talked_call_count,
			SUM(IF(t1.status='1',1,0)) AS not_talked_call_count,
			COUNT(DISTINCT t1.number) AS unique_call_count,
			SUM(IF(t1.bound_type='outgoing',1,0)) AS outgoing_call_count,
			SUM(IF(t1.bound_type='incoming',1,0)) AS incoming_call_count,
			SUM(IF(t1.status='0',1,0)) AS missing_opportunities_call_count,
			SUM(IF(t1.status='2',1,0)) AS new_leads_created_call_count,
			SUM(IF(t1.status='3',1,0)) AS sales_service_call_count,
			SUM(IF(t1.status='4',1,0)) AS other_business_count,
			t1.name,
			t1.country_code,
			t1.number,
			t1.call_start,
			t1.call_end,			
			t1.bound_type,
			t1.agent_mobile,
			t1.status,
			t3.status_wise_msg,
			t1.created_at,
			t2.name AS assigned_user_name,
			c_paying_info.* 			 
			FROM tbl_call_history_for_lead_tmp AS t1 
			INNER JOIN tbl_call_history_for_lead_tmp_tagged_lead AS t3 on t1.id=t3.call_history_id
			INNER JOIN user AS t2 ON t1.user_id=t2.id  
			$subsqlInner
			WHERE t1.is_deleted='N' $subsql $group_by_str $order_by_str ";  
			// echo $sql; die();
        $query = $this->client_db->query($sql,false);
        //echo $last_query = $this->client_db->last_query();die();
        //return $query->result();

		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
    }

	
	// CALL LOG LEADS
	// =====================================
	
    public function is_common_lead_pool_available($arg=array())
	{			
		$subsql='';
		if($arg['active_stages']){
			$subsql .=" AND current_stage_id IN (".$arg['active_stages'].")";
		}
		$sql="SELECT id FROM lead 
		WHERE assigned_user_id='0' $subsql";
		$query = $this->client_db->query($sql,false);
		if($query){
			if($query->num_rows()>0){
				return 'Y';
			}
			else{
				return 'N';
			}
		}
		else{
			return 'N';
		}
		
	}
	
	public function get_latest_lead_history($lid)
	{
		$sql="SELECT 
		t1.comment,
		t1.create_date,
		t2.name AS assigned_user_name
		FROM lead_comment AS t1 		
		LEFT JOIN user AS t2 ON t2.id=t1.user_id 
		WHERE t1.lead_id='".$lid."' 
		ORDER BY t1.id DESC LIMIT 1";
		//echo $sql; die();
		$query = $this->client_db->query($sql,false);
		if($query){
			return $query->row_array();	
		}
		else{
			return array();
		}
        	
	}
	
	public function remove_all_inactive_status_and_stage($lead_id,$client_info=array())
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
					
		$sql_delete="DELETE FROM lead_stage_log WHERE lead_id='".$lead_id."' AND stage_id IN ('3','5','6','7')";
		$this->client_db->query($sql_delete);
		
		$sql_delete2="DELETE FROM lead_status_log WHERE lead_id='".$lead_id."' AND status_id IN ('3','4')";
		$this->client_db->query($sql_delete2);
		return true;
		/*
		$stage_ids = array(3,5,6,7);
		$this->client_db->where_in('stage_id', $stage_ids);
		$this->client_db->where('lead_id', $lead_id);		
    	$this->client_db->delete('lead_stage_log');
		
		$status_ids = array(3,4);
		$this->client_db->where_in('status_id', $status_ids);
		$this->client_db->where('lead_id', $lead_id);
    	$this->client_db->delete('lead_status_log');
		*/
	}
	
	public function is_observer_available($arg=array())
	{			
		$subsql='';
		if($arg['active_stages']){
			$subsql .=" AND current_stage_id IN (".$arg['active_stages'].")";
		}
		$sql="SELECT id FROM lead 
		WHERE assigned_observer>0 $subsql";
		$query = $this->client_db->query($sql,false);
		if($query){
			if($query->num_rows()>0){
				return 'Y';
			}
			else{
				return 'N';
			}
		}
		else{
			return 'N';
		}
		
	}

	public function is_deal_won_lead($lead_id,$client_info=array())
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

		$subsql='';
		$sql="SELECT id FROM lead_stage_log WHERE lead_id='".$lead_id."' AND stage_id='4'";
		$query = $this->client_db->query($sql,false);
		if($query){
			if($query->num_rows()>0){
				return 'Y';
			}
			else{
				return 'N';
			}
		}
		else{
			return 'N';
		}
		
	}

	public function sms_lead_row($id,$client_info=array())
	{	
		if($this->class_name=='cronjobs' || $this->class_name=='capture')
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
		$sql="select 
				lead.id AS l_enquiry_id,
				lead.enquiry_date AS l_enquiry_date,
				lead.current_stage l_lead_stage,
				user.name AS l_assign_to,
				lead.followup_date AS l_next_followup_date,
				lead.title  AS l_lead_title,
				lead.current_stage_wise_msg AS l_lead_regret_reason 
				FROM lead 				 
				LEFT JOIN user on user.id=lead.assigned_user_id 
				WHERE lead.id='".$id."'";
		$result=$this->client_db->query($sql);
		// echo $last_query = $this->client_db->last_query();die();
		return $result->row_array();

		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	

	function get_dashboard_report_count($argument=NULL,$client_info=array())
    {       
		if($this->class_name=='rest_dashboard_report')
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
			$subsql.=" AND t1.user_id IN (".$argument['assigned_user'].")";
			$subsql2 .=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}
		
		if($argument['user_id']!='')
		{
			$subsql2 .=" OR lead.assigned_observer='".$argument['user_id']."'";
			$subsql2 .=" OR t1.user_id='".$argument['user_id']."'";
		}
        // SEARCH VALUE
        // --------------------------------------- 
		$total_call_count=0; 
		$total_meeting_count=0; 
		$sql="SELECT COUNT(t1.id) AS total_call_count 
		FROM lead_wise_call_request_log AS t1 
		WHERE t1.call_status IN ('N','O') $subsql 
		GROUP BY t1.user_id";
        $query = $this->client_db->query($sql,false);
		if($query){
			$row=$query->row_array();
			$total_call_count=$row['total_call_count'];
			
		}
		$sql2="SELECT COUNT(t1.id) AS total_meeting_count 
		FROM meeting AS t1 INNER JOIN lead ON lead.id=t1.lead_id WHERE t1.status_id IN ('1','2') $subsql2 GROUP BY t1.user_id";
        $query2 = $this->client_db->query($sql2,false);
		if($query2){
			$row2=$query2->row_array();
			$total_meeting_count=$row2['total_meeting_count'];
		}
		return array(
			'total_call_count'=>$total_call_count,
			'total_meeting_count'=>$total_meeting_count
		);		
        
    }

	// =================================================================
	// note
	public function add_note($data)
	{
		if($this->client_db->insert('lead_wise_note',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}		

	function get_note_rows($arg=array())
	{  
		if($arg['lead_id'])
		{
			$subsql .=" AND t1.lead_id='".$arg['lead_id']."'";
		}

		if($arg['self_and_under_user_id'])
		{
			$subsql .=" AND t1.user_id IN (".$arg['self_and_under_user_id'].")";
		}

		if($arg['nids'])
		{
			$subsql .=" AND t1.id IN (".$arg['nids'].")";
		}
		$sql="select 
			t1.id,
			t1.parent_id,
			t1.lead_id,
			t1.user_id,
			t1.note,
			t1.created_at,
			t2.name AS user_name
			FROM lead_wise_note AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id 
			WHERE t1.is_deleted='N' $subsql ORDER BY t1.id DESC ";
		$query = $this->client_db->query($sql);
		$arr = array();
		if($query){
			if($query->num_rows() > 0)
			{    
				foreach($query->result() as $row)
				{ 
					$arr[$row->id] = array(
									'parent_id'=> $row->parent_id,
									'note'=> $row->note,
									'created_at'=> $row->created_at,
									'user_name'=> $row->user_name,
									'parent_note'=> $row->parent_note								
									);
				}
			} 
		}		           
		return $arr;
	} 

	function get_note_list_count($argument=NULL)
    {       
        $subsqlInner='';
        // ---------------------------------------
        // SEARCH VALUE 
        if($argument['self_and_under_user_id']!='')
		{
			$subsql.=" AND t3.assigned_user_id IN (".$argument['self_and_under_user_id'].")";
		}

		if($argument['parent_id']!='')
		{
			$subsql.=" AND t1.parent_id ='".$argument['parent_id']."'";
		}
		
		if($argument['filter_by_keyword']!='')
		{			
			$subsql .= " AND  (t1.lead_id='".$argument['filter_by_keyword']."' || t1.note LIKE '%".$argument['filter_by_keyword']."%' || t4.company_name LIKE '%".$argument['filter_by_keyword']."%')";
		}

		if($argument['filter_note_from_date']!='' && $argument['filter_note_to_date']!='')
		{			
			
			$from = date_display_format_to_db_format($argument['filter_note_from_date']);  
			$to = date_display_format_to_db_format($argument['filter_note_to_date']);  
			$subsql.=" AND (DATE(t1.created_at) BETWEEN '".$from."' AND '".$to."') ";
		}
		
		if($argument['filter_note_added_by']!='')
		{
			$subsql.=" AND t1.user_id IN (".$argument['filter_note_added_by'].")";
		}

		if($argument['filter_lead_assign_to']!='')
		{
			$subsql.=" AND t3.assigned_user_id IN (".$argument['filter_lead_assign_to'].")";
		}
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id
			FROM lead_wise_note AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id 
			INNER JOIN lead AS t3 ON t1.lead_id=t3.id 
			INNER JOIN customer AS t4 ON t3.customer_id=t4.id
			INNER JOIN user AS t5 ON t3.assigned_user_id=t5.id 
			LEFT JOIN (
				SELECT count(lead_id) AS note_count,lead_id AS lid FROM lead_wise_note GROUP BY lead_id
			) AS nc ON nc.lid=t1.lead_id
			$subsqlInner 			
			WHERE t1.is_deleted='N' $subsql GROUP BY t1.id";
        $query = $this->client_db->query($sql,false);     
        if($query->num_rows() > 0) {
            return $query->num_rows();
        }
        else {
            return 0;
        }
    }

	function get_note_list($argument=NULL)
    {       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
        $order_by_str = " ORDER BY t1.created_at DESC ";
        if($argument['filter_sort_by']!='')
        {			
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 
        if($argument['self_and_under_user_id']!='')
		{
			$subsql.=" AND t3.assigned_user_id IN (".$argument['self_and_under_user_id'].")";
		}

		if($argument['parent_id']!='')
		{
			$subsql.=" AND t1.parent_id ='".$argument['parent_id']."'";
		}
		
		if($argument['filter_by_keyword']!='')
		{			
			$subsql .= " AND  (t1.lead_id='".$argument['filter_by_keyword']."' || t1.note LIKE '%".$argument['filter_by_keyword']."%' || t4.company_name LIKE '%".$argument['filter_by_keyword']."%')";
		}

		if($argument['filter_note_from_date']!='' && $argument['filter_note_to_date']!='')
		{			
			
			$from = date_display_format_to_db_format($argument['filter_note_from_date']);  
			$to = date_display_format_to_db_format($argument['filter_note_to_date']);  
			$subsql.=" AND (DATE(t1.created_at) BETWEEN '".$from."' AND '".$to."') ";
		}
		
		if($argument['filter_note_added_by']!='')
		{
			$subsql.=" AND t1.user_id IN (".$argument['filter_note_added_by'].")";
		}

		if($argument['filter_lead_assign_to']!='')
		{
			$subsql.=" AND t3.assigned_user_id IN (".$argument['filter_lead_assign_to'].")";
		}
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id,
			t1.parent_id,
			t1.lead_id,
			t1.user_id,
			t1.note,
			t1.created_at,
			t2.name AS note_added_by,
			t4.company_name,
			t5.name AS assigned_user_name,
			nc.*
			FROM lead_wise_note AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id 
			INNER JOIN lead AS t3 ON t1.lead_id=t3.id 
			INNER JOIN customer AS t4 ON t3.customer_id=t4.id
			INNER JOIN user AS t5 ON t3.assigned_user_id=t5.id 
			LEFT JOIN (
				SELECT count(lead_id) AS note_count,lead_id AS lid FROM lead_wise_note GROUP BY lead_id
			) AS nc ON nc.lid=t1.lead_id
			$subsqlInner 			
			WHERE t1.is_deleted='N' $subsql GROUP BY t1.id $order_by_str 
			LIMIT $start,$limit"; 
			// echo $sql; die();
        $query = $this->client_db->query($sql,false);
        // echo $last_query = $this->client_db->last_query();die();
        // return $query->result();
		$arr=array();
		if($query->num_rows() > 0)
		{    
			foreach($query->result() as $row)
			{ 
				$get_under_note_ids=array();
				$get_under_note_ids=$this->get_note_reply_is_seen($row->id,$row->lead_id);

				// $is_note_reply_seen='';
				// $is_note_reply_seen=$this->get_note_reply_is_seen($row->id,$row->lead_id);
				$arr[] = (object)array(
									'id'=> $row->id,
									'parent_id'=> $row->parent_id,
									'lead_id'=> $row->lead_id,
									'user_id'=> $row->user_id,
									'note'=> $row->note,								
									'created_at'=> $row->created_at,
									'note_added_by'=> $row->note_added_by,
									'company_name'=> $row->company_name,
									'assigned_user_name'=>$row->assigned_user_name,
									'note_count'=> $row->note_count,
									'total_reply_ids'=>$get_under_note_ids,
									'is_note_reply_seen'=>$is_note_reply_seen
								);
			}
		}  
		return $arr;
    }

	function get_note_reply_is_seen($note_id=0,$lead_id='')
    {       
        $result = array();  
        $subsql = '';
        $subsql .= " AND t1.id='".$note_id."'";    
		if($lead_id)
		{
			$subsql .= " AND t1.lead_id='".$lead_id."'";
		}   
        $sql="SELECT t1.id FROM lead_wise_note AS t1 WHERE t1.is_deleted='N' $subsql ORDER BY t1.id DESC";
        $query = $this->client_db->query($sql,false);        
        // return $last_query = $this->client_db->last_query();die();
        //return $query->result_array();         
        if($query->num_rows())
        {
        	$i=1;
			$this->note_arr=array();
			// $this->is_note_reply_seen='Y';
        	foreach($query->result() as $row)
	        {
	        	$this->get_note_reply_nth_level_is_seen($row->id,$lead_id);
	        	$i++;
	        }
        }
		// return $this->is_note_reply_seen;
        return array_keys($this->note_arr);
    }

	function get_note_reply_nth_level_is_seen($note_id=0,$lead_id='')
    {       	
        $result = array();  
        $subsql = '';        
        $subsql .= " AND t1.parent_id='".$note_id."'";   
		if($lead_id)
		{
			$subsql .= " AND t1.lead_id='".$lead_id."'";
		}      
        $sql="SELECT t1.id,CONCAT(t1.id,'#',t1.is_seen) AS id_with_is_seen,t1.is_seen FROM lead_wise_note AS t1 WHERE t1.is_deleted='N' $subsql ORDER BY t1.id DESC";       
		// echo $sql; die();
        $query = $this->client_db->query($sql,false);        
        // return $last_query = $this->client_db->last_query();
        //return $query->result_array();  
        $tmp_arr = array();
        if($query->num_rows())
        {
        	foreach($query->result() as $row)
	        {
				// if($row->is_seen=='N'){
				// 	$this->is_note_reply_seen='N';					
				// 	break;
				// }
				// else{
				// 	$this->get_note_reply_nth_level_is_seen($row->id);
				// }				
	        	$this->note_arr[$row->id_with_is_seen] = $this->get_note_reply_nth_level_is_seen($row->id);
	        }
        }  
    }

	function get_under_note_ids($note_id=0,$lead_id='')
    {       
        $result = array();  
        $subsql = '';
        $subsql .= " AND t1.id='".$note_id."'";    
		if($lead_id)
		{
			$subsql .= " AND t1.lead_id='".$lead_id."'";
		}   
        $sql="SELECT t1.id FROM lead_wise_note AS t1 WHERE t1.is_deleted='N' $subsql ORDER BY t1.id DESC";       

        $query = $this->client_db->query($sql,false);        
        // return $last_query = $this->client_db->last_query();die();
        //return $query->result_array();         
        if($query->num_rows())
        {
        	$i=1;
			$this->note_arr=array();
        	foreach($query->result() as $row)
	        {
	        	$this->get_note_id_nth_level($row->id,$lead_id);
	        	$i++;
	        }
        }
        return array_keys($this->note_arr);
    }

	function get_note_id_nth_level($note_id=0,$lead_id='')
    {       	
        $result = array();  
        $subsql = '';        
        $subsql .= " AND t1.parent_id='".$note_id."'";   
		if($lead_id)
		{
			$subsql .= " AND t1.lead_id='".$lead_id."'";
		}      
        $sql="SELECT t1.id FROM lead_wise_note AS t1 WHERE t1.is_deleted='N' $subsql ORDER BY t1.id DESC";       
		// echo $sql; die();
        $query = $this->client_db->query($sql,false);        
        // return $last_query = $this->client_db->last_query();
        //return $query->result_array();  
        $tmp_arr = array();
        if($query->num_rows())
        {
        	foreach($query->result() as $row)
	        {
	        	$this->note_arr[$row->id] = $this->get_note_id_nth_level($row->id);
	        }
        }  
    }

	function update_note_seen_status($to_user_id,$lead_id)
	{		
		$sql="SELECT id FROM lead_wise_note WHERE lead_id='".$lead_id."' AND to_user_id='".$to_user_id."' AND is_seen='N'"; 
		$query = $this->client_db->query($sql,false);     
		if($query){
			if($query->num_rows())
			{
				$this->client_db->where('lead_id',$lead_id);
				$this->client_db->where('to_user_id',$to_user_id);
				if($this->client_db->update('lead_wise_note',array('is_seen'=>'Y')))
				{
					return true;
				}
				else
				{
					// echo $last_query = $this->client_db->last_query(); die();
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		
		
	}
	// note
	// =================================================================

	public function GetCustomerWiseLeads($arg,$client_info=array())
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
		$subsql='';		
		if($arg['customer_id'])
		{
			$subsql .=" AND t1.customer_id='".$arg['customer_id']."'";
		}
		$sql="SELECT t1.id,t1.title,t1.current_stage
			FROM lead AS t1 		
			WHERE 1=1 $subsql ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	

	function GetAllOppStatus()
	{
		$subsql="";
		$sql="SELECT id,name FROM opportunity_status WHERE 1=1 $subsql ORDER BY sort ASC";
		$result=$this->client_db->query($sql);
		//return $result->result_array();

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetLeadDataForQuotationPopup($id,$client_info=array())
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
		$sql="select 
				lead.id,
				lead.customer_id,
				lead.assigned_user_id,
				lead.enquiry_date,
				lead.current_stage_id,
				lead.current_stage,
				cus.email as cus_email,
				cus.alt_email as cus_alt_email,
				cus.mobile as cus_mobile 
				FROM lead 
				INNER JOIN customer as cus on cus.id=lead.customer_id WHERE lead.id='".$id."'";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	// =================================================================================================
	// =================================================================================================
	// API
	public function get_customer_wise_lead_list($argument=array(),$client_info=array())
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

        $result = array();        
        $subsql = '';   
		$subsqlInner='';
		$subsqlHaving='';       
        $order_by_str = " ORDER BY lead.id DESC ";
        

        // ---------------------------------------
        // SEARCH VALUE 
		if($argument['c_id'])
		{
			$subsql.=" AND lead.customer_id='".$argument['c_id']."'";			
		}
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}


		$subsqlInner.=" LEFT JOIN 
							(SELECT t1.*
								FROM 
								(
									SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
									FROM 
									(
										SELECT t1.id,t1.lead_id,t1.stage_id,t2.name,t2.sort,t3.total_stage
										FROM `lead_stage_log` as t1 
										JOIN (SELECT * FROM `opportunity_stage` WHERE id NOT IN (3,4,5,6,7) AND `is_active_lead`='Y' AND `is_deleted` = 'N' ORDER BY sort) AS t2 ON t1.stage_id = t2.id
																	
										JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1 
										JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id 
										ORDER BY `lead_id`,t2.sort,t2.id
									) AS t
								) AS t1				
								WHERE last_stage_id IS NOT null	GROUP BY lead_id
							) AS ll_stage ON lead.id=ll_stage.lead_id";
		
		
        // SEARCH VALUE
        // ---------------------------------------
		//echo $subsql; die();
		$sql="SELECT 
			lo.*,
			lo2.*,
			lo3.*,
			lead.id,
			lead.title,
			lead.customer_id,
			lead.source_id,
			lead.assigned_user_id,
			lead.buying_requirement,
			lead.enquiry_date,
			lead.followup_date,
			lead.modify_date,
			lead.current_stage_id,			
			lead.current_stage,
			lead.current_stage_wise_msg,			
			lead.is_hotstar,
			lead.closer_date,
			lead.deal_value,
			lead.deal_value_currency_code,
			opportunity_status.name AS current_status,
			user.name AS assigned_user_name,
			observer.name AS assigned_observer_name,
			c_paying_info.*,
			c_r_c.*,
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
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,
			source.name AS source_name,
			source.alias_name AS source_alias_name,
			im_setting.account_name AS im_account_name,
			GROUP_CONCAT(stage_log.stage_id ORDER BY stage_log.id) AS stage_logs,
			GROUP_CONCAT(DISTINCT CONCAT(tagged_ps.id,'#',tagged_ps.name)) AS tagged_ps,
			COUNT(DISTINCT note.id) AS note_count,
			coalesce(meeting.meeting_count,0) as meeting_count,
			coalesce(meeting.meeting_completed_count,0) as meeting_completed_count,
			coalesce(call_history.call_count,0) as call_count,
			coalesce(call_history.talked_call_count,0) as talked_call_count,
			ll_stage.name AS ll_stage_name,
			ll_stage.stage_id AS ll_stage_id
			FROM lead 
			LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead WHERE status='1' GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id
			LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			LEFT JOIN source ON source.id=lead.source_id 
			LEFT JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN user AS observer ON observer.id=lead.assigned_observer
			LEFT JOIN (
				SELECT 
				COUNT(id) AS proposal,
				lead_id AS lo_lid,
				SUM(deal_value) AS total_deal_value,
				SUM(deal_value_as_per_purchase_order) AS total_deal_value_as_per_purchase_order
				FROM lead_opportunity GROUP BY lead_id
			) AS lo ON lo.lo_lid=lead.id
			LEFT JOIN (
				SELECT lead_id AS lo_lid2,
				SUBSTRING_INDEX(GROUP_CONCAT(deal_value),',', -1) AS quotation_sent_deal_value,
				currency.code AS quotation_sent_currency_code
				FROM lead_opportunity 
				INNER JOIN currency ON lead_opportunity.currency_type=currency.id 
				WHERE status='2' GROUP BY lead_id
			) AS lo2 ON lo2.lo_lid2=lead.id
			LEFT JOIN (
				SELECT lead_id AS lo_lid3,
				SUBSTRING_INDEX(GROUP_CONCAT(deal_value),',', -1) AS quotation_matured_deal_value,
				SUBSTRING_INDEX(GROUP_CONCAT(deal_value_as_per_purchase_order),',', -1) AS quotation_matured_deal_value_as_per_purchase_order,
				currency.code AS quotation_matured_currency_code
				FROM lead_opportunity 
				INNER JOIN currency ON lead_opportunity.currency_type=currency.id  
				WHERE status='4' GROUP BY lead_id
			) AS lo3 ON lo3.lo_lid3=lead.id
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			LEFT JOIN indiamart_setting AS im_setting ON lead.im_setting_id=im_setting.id 
			LEFT JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id 
			LEFT JOIN lead_wise_product_service AS tagged_ps ON tagged_ps.lead_id=lead.id AND tagged_ps.tag_type='L' 
			LEFT JOIN lead_wise_note AS note ON lead.id=note.lead_id 
			LEFT JOIN opportunity_status ON lead.current_status_id=opportunity_status.id 
			LEFT JOIN (select lead_id,COUNT(id)
			AS meeting_count,
				COUNT(
					IF(
						status_id = 3,
						id,
						NULL
					)
				) AS meeting_completed_count
				from meeting where lead_id is not null group by lead_id
			) as meeting ON lead.id = meeting.lead_id 
			LEFT JOIN (select tagged_lead_id,COUNT(id)
			AS call_count,
				COUNT(
					IF(
						status != 1,
						id,
						NULL
					)
				) AS talked_call_count
				from tbl_call_history_for_lead_tmp where tagged_lead_id is not null group by tagged_lead_id
			) AS call_history 
			ON lead.id = call_history.tagged_lead_id 
			
			$subsqlInner WHERE lead.status='1' $subsql GROUP BY lead.id $subsqlHaving $order_by_str";
		//  echo $sql;die();
        $query = $this->client_db->query($sql,false);        
        $last_query = $this->client_db->last_query();
        //return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
    }
	// API
	// =================================================================================================
	// =================================================================================================
}
?>