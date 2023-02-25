<?php
class Dashboard_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	function __construct() 
	{
        parent::__construct();
		// $this->load->database();
		$this->user_arr=array();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    }
	
	public function get_dashboard_summery_count($argument)
	{		
		$company=get_company_profile($client_info);
		$company_country_id=$company['country_id'];
		$subsql='';
		$today_date=date("Y-m-d");
		// ------------------------------------
        // ACTIVE LEADS IDS
        $sql="SELECT id FROM opportunity_stage 
            WHERE id NOT IN ('3','4','5','6','7')";
        $result=$this->client_db->query($sql);
		$active_lead_count_str='';
		$active_lead_ids='';
		if($result){
			if($result->num_rows())
			{
				
				$rows=$result->result();
				foreach($rows AS $row)
				{
					$active_lead_count_str .="lead.current_stage_id='".$row->id."' || ";
					$active_lead_ids .="'".$row->id."',";
				}
				$active_lead_count_str=rtrim($active_lead_count_str, ' || ');
				$active_lead_ids=rtrim($active_lead_ids, ',');
			}
		}
        
        // ACTIVE LEADS IDS
        // ------------------------------------
		//SUM(if(cus.country_id!='".$company_country_id."' && ($active_lead_count_str),1,0)) AS foreign_lead_count,
		//SUM(if(cus.country_id='".$company_country_id."' && ($active_lead_count_str),1,0)) AS domestic_lead_count
		if($argument['filter_selected_user_id']!='')
		{		
			$subsql.=" AND lead.assigned_user_id IN (".$argument['filter_selected_user_id'].")";			
		}		
		$sql = "SELECT 
                group_concat(lead.id) AS total_lead_ids,
                lead.assigned_user_id,
				COUNT(lead.id) AS lead_count,
				SUM(if($active_lead_count_str,1,0)) AS active_lead_count,
				SUM(if(lead.is_followup_date_changed='N' && ($active_lead_count_str),1,0)) AS new_lead_count,
				SUM(if(lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)='".$today_date."' && ($active_lead_count_str),1,0)) AS today_followup_count,
				SUM(if(lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)<'".$today_date."' && ($active_lead_count_str),1,0)) AS pending_followup_count,
				SUM(if(lead.is_followup_date_changed='Y' AND DATE(lead.followup_date)>'".$today_date."' && ($active_lead_count_str),1,0)) AS upcoming_followup_count,
				SUM(if(lead.current_stage_id='2',1,0)) AS quoted_lead_count,
				SUM(if(lead.current_stage_id='6' || lead.current_stage_id='7',1,0)) AS auto_regretted_lead_count								
				FROM lead 
				LEFT JOIN customer AS cus ON cus.id=lead.customer_id
				WHERE lead.status='1' $subsql GROUP BY lead.assigned_user_id";
				
		$sql_q = "SELECT 
				lead.id AS lid
				FROM lead 				
				INNER JOIN customer AS cus ON cus.id=lead.customer_id 				 
				INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id 
				AND stage_log_inner.stage_id IN (2) 
				WHERE lead.status='1' $subsql AND ($active_lead_count_str) GROUP BY lead.id";
				
		
		$query = $this->client_db->query($sql,false); 
		$query_q = $this->client_db->query($sql_q,false); 
		$active_lead_count=0;
		$new_lead_count=0;
		$today_followup_count=0;
		$pending_followup_count=0;
		$upcoming_followup_count=0;
		$quoted_lead_count=0;
		$auto_regretted_lead_count=0;
		$foreign_lead_count=0;
		$domestic_lead_count=0;
		if($query_q){
			if($query_q->num_rows() > 0) 
			{			
				foreach($query_q->result() AS $row_q)
				{
					$quoted_lead_count++;
				}
			}
		}
		
		if($query){
			if($query->num_rows() > 0) 
			{
				//$this->client_db->last_query();
				foreach($query->result() AS $row)
				{
					
					$active_lead_count=$active_lead_count+$row->active_lead_count;
					$new_lead_count=$new_lead_count+$row->new_lead_count;
					$today_followup_count=$today_followup_count+$row->today_followup_count;
					$pending_followup_count=$pending_followup_count+$row->pending_followup_count;
					$upcoming_followup_count=$upcoming_followup_count+$row->upcoming_followup_count;
					//$quoted_lead_count=$quoted_lead_count+$row->quoted_lead_count;
					$auto_regretted_lead_count=$auto_regretted_lead_count+$row->auto_regretted_lead_count;				
					$foreign_lead_count=$foreign_lead_count+$row->foreign_lead_count;
					$domestic_lead_count=$domestic_lead_count+$row->domestic_lead_count;
				}

				// percentage
				$new_lead_count_per=(int)(($new_lead_count/$active_lead_count)*100);
				$today_followup_count_per=(int)(($today_followup_count/$active_lead_count)*100);
				$pending_followup_count_per=(int)(($pending_followup_count/$active_lead_count)*100);
				$upcoming_followup_count_per=(int)(($upcoming_followup_count/$active_lead_count)*100);
				$quoted_lead_count_per=(int)(($quoted_lead_count/$active_lead_count)*100);	
				$auto_regretted_lead_count_per=(int)(($auto_regretted_lead_count/$active_lead_count)*100);
				$foreign_lead_count_per=(int)(($foreign_lead_count/$active_lead_count)*100);
				$domestic_lead_count_per=(int)(($domestic_lead_count/$active_lead_count)*100);
			}
		}
		
		
		
		$return_arr=array();
		$return_arr['active_lead_count']=$active_lead_count;
		$return_arr['new_lead_count']=$new_lead_count;
		$return_arr['today_followup_count']=$today_followup_count;
		$return_arr['pending_followup_count']=$pending_followup_count;
		$return_arr['upcoming_followup_count']=$upcoming_followup_count;
		$return_arr['quoted_lead_count']=$quoted_lead_count;
		$return_arr['auto_regretted_lead_count']=$auto_regretted_lead_count;		
		$return_arr['foreign_lead_count']=$foreign_lead_count;
		$return_arr['domestic_lead_count']=$domestic_lead_count;
		
		// percentage
		$return_arr['new_lead_count_per']=$new_lead_count_per;
		$return_arr['today_followup_count_per']=$today_followup_count_per;
		$return_arr['pending_followup_count_per']=$pending_followup_count_per;
		$return_arr['upcoming_followup_count_per']=$upcoming_followup_count_per;
		$return_arr['quoted_lead_count_per']=$quoted_lead_count_per;
		$return_arr['auto_regretted_lead_count_per']=$auto_regretted_lead_count_per;		
		$return_arr['foreign_lead_count_per']=$foreign_lead_count_per;
		$return_arr['domestic_lead_count_per']=$domestic_lead_count_per;
		
		
		/*
		// if($argument['filter_is_count_or_percentage']!='')
		// {			
		// 	$subsql.=" AND assigned_user_id IN (".$argument['filter_is_count_or_percentage'].")";
		// }
		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}
		$sql="SELECT * FROM dashboard_summery_count_report 
			  WHERE 1=1 $subsql GROUP BY assigned_user_id";
		$query = $this->client_db->query($sql,false);  
		
		$lead_count=0;
		$active_lead=0;
		$quoted=0;
		$pending=0;
		$foreign=0;
		$domestic=0;
		$star_marked=0;
		$un_followed=0;
		// count
		$quoted_tmp=0;
		$pending_tmp=0;
		$foreign_tmp=0;
		$domestic_tmp=0;
		$star_marked_tmp=0;
		$un_followed_tmp=0;
		// percentage
		$active_lead_tmp_percentage=0;
		$quoted_tmp_per=0;
		$pending_tmp_per=0;
		$foreign_tmp_per=0;
		$domestic_tmp_per=0;
		$star_marked_tmp_per=0;
		$un_followed_tmp_per=0;	

		if($query->num_rows() > 0) 
		{
			//$this->client_db->last_query();
			foreach($query->result() AS $row)
			{
				$lead_count=$lead_count+$row->lead_count;
				$active_lead=$active_lead+$row->active_lead_count;
				$quoted=$quoted+$row->quoted_lead_count;
				$pending=$pending+$row->pending_lead_count;
				$foreign=$foreign+$row->foreign_lead_count;
				$domestic=$domestic+$row->domestic_lead_count;
				$star_marked=$star_marked+$row->star_marked_lead_count;
				$un_followed=$un_followed+$row->un_followed_lead_count;
			}

			// count
			$quoted_tmp=$quoted;
			$pending_tmp=$pending;
			$foreign_tmp=$foreign;
			$domestic_tmp=$domestic;
			$star_marked_tmp=$star_marked;
			$un_followed_tmp=$un_followed;

			// percentage			
			$quoted_tmp_per=(int) (($quoted/$active_lead)*100);
			$pending_tmp_per=(int)(($pending/$active_lead)*100);
			$foreign_tmp_per=(int)(($foreign/$active_lead)*100);
			$domestic_tmp_per=(int)(($domestic/$active_lead)*100);
			$star_marked_tmp_per=(int)(($star_marked/$active_lead)*100);
			$un_followed_tmp_per=(int)(($un_followed/$active_lead)*100);			
		}
		$return_arr=array();
		$return_arr['lead_count']=$lead_count;
		$return_arr['active_lead']=$active_lead;
		$return_arr['quoted']=$quoted_tmp;
		$return_arr['pending']=$pending_tmp;
		$return_arr['foreign']=$foreign_tmp;
		$return_arr['domestic']=$domestic_tmp;
		$return_arr['star_marked']=$star_marked_tmp;
		$return_arr['un_followed']=$un_followed_tmp;

		
		$return_arr['quoted_percentage']=$quoted_tmp_per;
		$return_arr['pending_percentage']=$pending_tmp_per;
		$return_arr['foreign_percentage']=$foreign_tmp_per;
		$return_arr['domestic_percentage']=$domestic_tmp_per;
		$return_arr['star_marked_percentage']=$star_marked_tmp_per;
		$return_arr['un_followed_percentage']=$un_followed_tmp_per;
		*/

		return $return_arr;
	}
	
	public function get_latest_lead($argument)
	{
		$start=$argument['start'];
		$limit=$argument['limit'];
		$subsql='';
		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}
		$sql="SELECT * FROM lead WHERE (current_stage_id='1' OR current_stage_id='2') $subsql 
				ORDER BY id DESC LIMIT $start,$limit";
		$query = $this->client_db->query($sql,false); 
		if($query){
			return $query->result();
		} 
		else{
			return (object)array();
		}
		
	}
	
	public function get_pending_followup_lead($argument)
	{		
		$subsql='';
		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}
		$sql="SELECT * FROM lead 
		WHERE followup_date<='".date('Y-m-d')."' AND (current_stage_id='1' OR current_stage_id='2') $subsql 
		ORDER BY followup_date ASC";
		$query = $this->client_db->query($sql,false);  
		//return $query->result();

		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}
	
	
	
	public function get_business_report_weekly_count($argument)
	{
		$subsql='';
		if($argument['filter_business_report_weekly_period']!='')
		{			
			$subsql.=" AND DATE_FORMAT(date, '%Y-%m')='".$argument['filter_business_report_weekly_period']."'";
			$group_by=" GROUP BY date";
			$date_str="date";			
		}

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}
		$sql="SELECT * FROM dashboard_day_wise_sales_report 
			WHERE 1=1 $subsql $group_by";
		$query = $this->client_db->query($sql,false);  
		return $query->num_rows();
	}
	
	public function get_business_report_weekly($argument)
	{
		$subsql='';
		$start=$argument['start'];
		$limit=$argument['limit'];
		$date_str='';
		if($argument['filter_business_report_weekly_period']!='')
		{			
			$subsql.=" AND DATE_FORMAT(date, '%Y-%m')='".$argument['filter_business_report_weekly_period']."'";
			$group_by=" GROUP BY date";
			$date_str="date";			
		}

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}
		$sql="SELECT 
			SUM(new_lead_count) AS total_lead_count,
			SUM(quoted_lead_count) AS total_quoted_lead_count,
			SUM(regretted_lead_count) AS total_regretted_lead_count,
			SUM(pending_lead_count) AS total_pending_lead_count,
			SUM(deal_won_lead_count) AS total_deal_won_lead_count,
			SUM(deal_lost_lead_count) AS total_deal_lost_lead_count,
			SUM(revenue) AS total_revenue,
			GROUP_CONCAT(revenue_wise_currency SEPARATOR '@') AS total_revenue_wise_currency,
			$date_str AS date_str FROM dashboard_day_wise_sales_report 
			WHERE 1=1 $subsql $group_by ORDER BY date DESC LIMIT $start,$limit";
		$query = $this->client_db->query($sql,false);  
		//return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}
	public function get_business_report_monthly_count($argument)
	{
		$subsql='';		
		if($argument['filter_business_report_monthly_period']!='')
		{			
			$subsql.=" AND DATE_FORMAT(date, '%Y')='".$argument['filter_business_report_monthly_period']."'";
			$group_by=" GROUP BY month(date)";
		}

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}
		$sql="SELECT * FROM dashboard_day_wise_sales_report 
			WHERE 1=1 $subsql $group_by";
		$query = $this->client_db->query($sql,false);  
		return $query->num_rows();
	}
	
	public function get_business_report_monthly($argument)
	{
		$subsql='';
		$start=$argument['start'];
		$limit=$argument['limit'];
		$date_str='';
		if($argument['filter_business_report_monthly_period']!='')
		{			
			$subsql.=" AND DATE_FORMAT(date, '%Y')='".$argument['filter_business_report_monthly_period']."'";
			$group_by=" GROUP BY month(date)";
			$date_str="CONCAT(MONTHNAME(date),'-',YEAR(date))";	
		}

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}
		$sql="SELECT 
			SUM(new_lead_count) AS total_lead_count,
			SUM(quoted_lead_count) AS total_quoted_lead_count,
			SUM(regretted_lead_count) AS total_regretted_lead_count,
			SUM(pending_lead_count) AS total_pending_lead_count,
			SUM(deal_won_lead_count) AS total_deal_won_lead_count,
			SUM(deal_lost_lead_count) AS total_deal_lost_lead_count,
			SUM(revenue) AS total_revenue,
			GROUP_CONCAT(revenue_wise_currency SEPARATOR '@') AS total_revenue_wise_currency,
			$date_str AS date_str FROM dashboard_day_wise_sales_report 
			WHERE 1=1 $subsql $group_by ORDER BY date DESC LIMIT $start,$limit";
		$query = $this->client_db->query($sql,false);  
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function GetCurrencyList()
	{
		$sql="SELECT * FROM currency ORDER BY id";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_user_wise_pending_followup($arg=array())
	{
		$curr_date = date('Y-m-d');
		$current_time = strtotime($curr_date);
		$yesterday_date = date('Y-m-d', strtotime('-1 day', $current_time));
		$two_day_before = date('Y-m-d', strtotime('-2 day', $current_time));
		$five_day_before = date('Y-m-d', strtotime('-5 day', $current_time));

		$limit_str='';
		if($arg['limit'])
		{
			$limit_str=' LIMIT 0,'.$arg['limit'];
		}


		$subsql='';
		$subsql.=" AND (lead.current_stage_id IN (".$arg['al_stages'].") AND DATE(lead.followup_date)<='".$curr_date."')";
		//$subsql.=" AND (lead.current_stage_id NOT IN ('3','4','5','6','7') AND lead.followup_date<='".$curr_date."')";
		$sql="SELECT user.name AS assigned_user_name,
		lead.assigned_user_id,
		COUNT(lead.id) AS total_pending_count,
		SUM(if(DATE(lead.followup_date)='".$curr_date."',1,0)) AS today_pending_count,
		SUM(if(DATE(lead.followup_date)='".$yesterday_date."',1,0)) AS yesterday_pending_count,
		SUM(if(DATE(lead.followup_date)<='".$two_day_before."',1,0)) AS twoday_pending_count,
		SUM(if(DATE(lead.followup_date)<='".$five_day_before."',1,0)) AS fiveday_pending_count 
		FROM lead 
		INNER JOIN user ON lead.assigned_user_id=user.id
		WHERE lead.assigned_user_id IN (".$arg['u_ids_str'].") $subsql GROUP BY lead.assigned_user_id $limit_str";
		//echo $sql;die('');
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_unfollowed_leads_by_user($argument)
	{
		// $curr_date = date('Y-m-d');
		// $current_time = strtotime($curr_date);
		// $yesterday_date = date('Y-m-d', strtotime('-1 day', $current_time));
		// $two_day_before = date('Y-m-d', strtotime('-2 day', $current_time));
		// $five_day_before = date('Y-m-d', strtotime('-5 day', $current_time));

		$limit_str='';
		// if($limit)
		// {
		// 	$limit_str=' LIMIT 0,'.$limit;
		// }

		$subsql='';
		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND lead.assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (lead.enquiry_date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (lead.enquiry_date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}
		//$subsql.=" AND (lead.current_stage_id IN ('1','8','2','9') AND lead.followup_date<='".$curr_date."')";

		$sql="SELECT user.name AS assigned_user_name,
		lead.assigned_user_id,
		SUM(if(lead.current_stage_id IN ('7'),1,0)) AS prospect_lead_not_followed_count,
		SUM(if(lead.current_stage_id IN ('6'),1,0)) AS new_lead_not_followed_count  
		FROM lead 
		INNER JOIN user ON lead.assigned_user_id=user.id
		WHERE 1=1 $subsql GROUP BY lead.assigned_user_id ORDER BY (SUM(if(lead.current_stage_id IN ('7'),1,0))+SUM(if(lead.current_stage_id IN ('6'),1,0))) DESC $limit_str";
		// echo $sql;die();
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_product_vs_leads($argument)
	{
		$subsql='';
		// print_r($argument); die('model');
		$start=$argument['start'];
		$limit=$argument['limit'];
		$limit_str='';
		if($limit!="")
		{
			$limit_str=" LIMIT $start,$limit";
		}

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		$sql="SELECT product_name,
		SUM(total_lead_count) AS total_lead_count,
		SUM(quoted_lead_count) AS total_quoted_lead_count,
		SUM(deal_won_lead_count) AS total_deal_won_lead_count,
		SUM(deal_lost_lead_count) AS total_deal_lost_lead_count,
		SUM(regretted_lead_count) AS total_regretted_lead_count,
		SUM(auto_regretted_lead_count) AS total_auto_regretted_lead_count,
		SUM(auto_deal_lost_lead_count) AS total_auto_deal_lost_lead_count
		FROM dashboard_day_wise_product_report WHERE 1=1 $subsql GROUP BY product_name ORDER BY COUNT(total_lead_count) DESC $limit_str";
		// echo $sql;die();
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	
	
	public function get_user_activity_report_count($argument)
	{
		$subsql='';
		$start=$argument['start'];
		$limit=$argument['limit'];
		$date_str='';
		

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
				$end_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
				$end_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
				$end_date=date("Y-m-d",strtotime($current_date));
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
				$end_date=date("Y-m-d",strtotime($current_date));
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
				$end_date=date("Y-m-d",strtotime($current_date));
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				//$end_date=date("Y-m-d",strtotime($current_date));					
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}

		$sql="SELECT assigned_user_id FROM dashboard_day_wise_sales_report 
			WHERE 1=1 $subsql GROUP BY assigned_user_id";
		//echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
		
	}
	public function get_user_activity_report($argument)
	{
		$subsql='';
		$start=$argument['start'];
		$limit=$argument['limit'];
		$date_str='';
		

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
				$end_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
				$end_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
				$end_date=date("Y-m-d",strtotime($current_date));
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
				$end_date=date("Y-m-d",strtotime($current_date));
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
				$end_date=date("Y-m-d",strtotime($current_date));
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				//$end_date=date("Y-m-d",strtotime($current_date));					
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}


		$sql="SELECT t1.assigned_user_id,
			SUM(t1.new_lead_count) AS total_new_lead_count,
			SUM(t1.updated_lead_count) AS total_updated_lead_count,
			SUM(t1.quoted_lead_count) AS total_quoted_lead_count,
			SUM(t1.pending_lead_count) AS total_pending_lead_count,
			SUM(t1.regretted_lead_count) AS total_regretted_lead_count,
			SUM(t1.deal_lost_lead_count) AS total_deal_lost_lead_count,
			SUM(t1.deal_won_lead_count) AS total_deal_won_lead_count,
			SUM(t1.revenue) AS total_revenue,
			GROUP_CONCAT(t1.revenue_wise_currency SEPARATOR '@') AS total_revenue_wise_currency,
			SUM(t1.auto_regretted_lead_count) AS total_auto_regretted_lead_count,
			SUM(t1.auto_deal_lost_lead_count) AS total_auto_deal_lost_lead_count,
			t2.name AS assigned_user_name 
			FROM dashboard_day_wise_sales_report AS t1 
			INNER JOIN user AS t2 ON t1.assigned_user_id=t2.id
			WHERE 1=1 $subsql GROUP BY assigned_user_id ORDER BY SUM(new_lead_count) DESC LIMIT $start,$limit";
		//echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_lead_source_vs_conversion_report_count($argument)
	{
		$subsql='';
		$start=$argument['start'];
		$limit=$argument['limit'];
		$date_str='';
		

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}


		$sql="SELECT t1.source_id
			FROM dashboard_day_wise_source_report AS t1 
			WHERE 1=1 $subsql GROUP BY source_id";
		//echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
		
	}
	public function get_lead_source_vs_conversion_report($argument)
	{
		$subsql='';
		$start=$argument['start'];
		$limit=$argument['limit'];
		$date_str='';
		

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}


		$sql="SELECT t1.source_id,
			SUM(t1.total_lead_count) AS total_new_lead_count,
			SUM(t1.updated_lead_count) AS total_updated_lead_count,
			SUM(t1.quoted_lead_count) AS total_quoted_lead_count,
			SUM(t1.pending_lead_count) AS total_pending_lead_count,
			SUM(t1.regretted_lead_count) AS total_regretted_lead_count,
			SUM(t1.deal_lost_lead_count) AS total_deal_lost_lead_count,
			SUM(t1.deal_won_lead_count) AS total_deal_won_lead_count,
			SUM(t1.revenue) AS total_revenue,
			GROUP_CONCAT(t1.revenue_wise_currency SEPARATOR '@') AS total_revenue_wise_currency,
			SUM(t1.auto_regretted_lead_count) AS total_auto_regretted_lead_count,
			SUM(t1.auto_deal_lost_lead_count) AS total_auto_deal_lost_lead_count,
			t2.name AS source_name 
			FROM dashboard_day_wise_source_report AS t1 
			INNER JOIN source AS t2 ON t1.source_id=t2.id
			WHERE 1=1 $subsql GROUP BY source_id ORDER BY SUM(total_lead_count) DESC LIMIT $start,$limit";
		//echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_user_wise_c2c_report($argument)
	{
		$subsql='';		
		$date_str='';		

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND t1.user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (DATE(t1.created_at) BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (DATE(t1.created_at) BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}


		$sql="SELECT 
			t1.user_id,
			COUNT(t1.id) AS total_call_count,
			SUM(if(lower(t1.call_type)='incoming',0,1)) AS outbound_count,
			SUM(if(lower(t1.call_type)='incoming',1,0)) AS inbound_count,			
			SUM(if(t1.call_status='Y',1,0)) AS success_call_count,
			SUM(if(t1.call_status!='Y',1,0)) AS fail_call_count,
			t2.name AS user_name 
			FROM lead_wise_c2c_log AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id
			WHERE t1.uniquid IS NOT NULL $subsql GROUP BY t1.user_id ORDER BY COUNT(t1.id) DESC";
		// echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		//return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_particular_user_wise_c2c_report($argument)
	{
		$subsql='';		
		$date_str='';		

		if($argument['filter_type']!='all')
		{		
			if($argument['filter_type']=='outbound')
			{
				// $subsql.=" AND LOWER(t1.call_type) !='incoming'";
				$subsql.=" AND t1.lead_id>'0'";
			}
			else if($argument['filter_type']=='inbound')
			{
				$subsql.=" AND LOWER(t1.call_type) ='incoming'";
			}
			else if($argument['filter_type']=='success')
			{
				$subsql.=" AND t1.call_status ='Y'";
			}
			else if($argument['filter_type']=='fail')
			{
				$subsql.=" AND t1.call_status !='Y'";
			}			
		}


		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND t1.user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (DATE(t1.created_at) BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (DATE(t1.created_at) BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}


		$sql="SELECT t1.id,
			t1.lead_id,
			t1.user_id,
			t1.executive_mobile_number,
			t1.customer_contact_person,
			t1.client_mobile_number,
			t1.call_status,
			t1.call_status_txt,
			t1.call_type,
			t1.call_datetime,
			t1.exact_call_start,
			t1.exact_call_end,
			t1.call_recording_url,
			t1.c2c_url,
			t1.uniquid,
			t1.created_at,
			t1.updated_at,
			t2.name AS user_name,
			t3.company_name
			FROM lead_wise_c2c_log AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id
			LEFT JOIN customer AS t3 ON t1.customer_id=t3.id
			WHERE t1.uniquid IS NOT NULL $subsql ORDER BY t1.id DESC";
		//echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		//return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_date_wise_c2c_report($argument)
	{
		$subsql='';		
		$date_str='';		

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND t1.user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (DATE(t1.created_at) BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}

		$sql="SELECT COUNT(t1.id) AS total_call_count,
			SUM(if(lower(t1.call_type)='incoming',0,1)) AS outbound_count,
			SUM(if(lower(t1.call_type)='incoming',1,0)) AS inbound_count,			
			SUM(if(t1.call_status='Y',1,0)) AS success_call_count,
			SUM(if(t1.call_status!='Y',1,0)) AS fail_call_count,
			DATE(t1.created_at) AS created_at 
			FROM lead_wise_c2c_log AS t1 
			WHERE t1.uniquid IS NOT NULL $subsql GROUP BY DATE(t1.created_at) ORDER BY DATE(t1.created_at) DESC";
		// echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		//return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_particular_date_wise_c2c_report($argument)
	{
		$subsql='';		
		$date_str='';		

		if($argument['filter_type']!='all')
		{		
			if($argument['filter_type']=='outbound')
			{
				// $subsql.=" AND LOWER(t1.call_type) !='incoming'";
				$subsql.=" AND t1.lead_id>'0'";
			}
			else if($argument['filter_type']=='inbound')
			{
				$subsql.=" AND LOWER(t1.call_type) ='incoming'";
			}
			else if($argument['filter_type']=='success')
			{
				$subsql.=" AND t1.call_status ='Y'";
			}
			else if($argument['filter_type']=='fail')
			{
				$subsql.=" AND t1.call_status !='Y'";
			}			
		}

		if($argument['filter_selected_date']!='')
		{		
			$subsql.=" AND DATE(t1.created_at)='".$argument['filter_selected_date']."'";		
		}

		// if($argument['filter_selected_user_id']!='')
		// {			
		// 	$subsql.=" AND t1.user_id IN (".$argument['filter_selected_user_id'].")";
		// }

		// if($argument['filter_date_range_pre_define']!='')
		// {			
		// 	$current_date=date("Y-m-d");
		// 	if($argument['filter_date_range_pre_define']=='TODAY')
		// 	{
		// 		$last_day=0;
		// 	}
		// 	if($argument['filter_date_range_pre_define']=='YESTERDAY')
		// 	{
		// 		$last_day=1;
		// 	}
		// 	if($argument['filter_date_range_pre_define']=='LAST7DAYS')
		// 	{
		// 		$last_day=7;
		// 	}
		// 	if($argument['filter_date_range_pre_define']=='LAST15DAYS')
		// 	{
		// 		$last_day=15;
		// 	}
		// 	if($argument['filter_date_range_pre_define']=='LAST30DAYS')
		// 	{
		// 		$last_day=30;
		// 	}

		// 	if($argument['filter_date_range_pre_define']!='TILLDATE')
		// 	{
		// 		$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
		// 		$end_date=date("Y-m-d",strtotime($current_date));			
		// 		$subsql.=" AND (DATE(t1.created_at) BETWEEN '".$start_date."' AND '".$end_date."')";
		// 	}
			
		// }
		// else
		// {
		// 	if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
		// 	{
		// 		$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
		// 		$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
		// 		$subsql.=" AND (DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')";
		// 	}
		// }

		$sql="SELECT t1.id,
			t1.lead_id,
			t1.user_id,
			t1.executive_mobile_number,
			t1.customer_contact_person,
			t1.client_mobile_number,
			t1.call_status,
			t1.call_status_txt,
			t1.call_type,
			t1.call_datetime,
			t1.exact_call_start,
			t1.exact_call_end,
			t1.call_recording_url,
			t1.c2c_url,
			t1.uniquid,
			t1.created_at,
			t1.updated_at,
			t2.name AS user_name,
			t3.company_name
			FROM lead_wise_c2c_log AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id
			LEFT JOIN customer AS t3 ON t1.customer_id=t3.id
			WHERE t1.uniquid IS NOT NULL $subsql ORDER BY t1.id DESC";

		
		// echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		//return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}





	public function get_lead_source_vs_quality_report($argument)
	{
		$subsql='';
		$start=$argument['start'];
		$limit=$argument['limit'];
		$date_str='';
		

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}


		$sql="SELECT t1.source_id,
			SUM(t1.total_lead_count) AS total_new_lead_count,
			SUM(t1.updated_lead_count) AS total_updated_lead_count,
			SUM(t1.quoted_lead_count) AS total_quoted_lead_count,
			SUM(t1.pending_lead_count) AS total_pending_lead_count,
			SUM(t1.regretted_lead_count) AS total_regretted_lead_count,
			SUM(t1.deal_lost_lead_count) AS total_deal_lost_lead_count,
			SUM(t1.deal_won_lead_count) AS total_deal_won_lead_count,
			SUM(t1.revenue) AS total_revenue,
			GROUP_CONCAT(t1.revenue_wise_currency SEPARATOR '@') AS total_revenue_wise_currency,
			SUM(t1.auto_regretted_lead_count) AS total_auto_regretted_lead_count,
			SUM(t1.auto_deal_lost_lead_count) AS total_auto_deal_lost_lead_count,
			t2.name AS source_name 
			FROM dashboard_day_wise_source_report AS t1 
			INNER JOIN source AS t2 ON t1.source_id=t2.id
			WHERE 1=1 $subsql GROUP BY source_id ORDER BY SUM(total_lead_count) DESC LIMIT $start,$limit";
		//echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_lead_by_source_report($argument)
	{
		$subsql='';
		$start=$argument['start'];
		$limit=$argument['limit'];
		$date_str='';
		

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}


		$sql="SELECT t1.source_id,
			SUM(t1.total_lead_count) AS total_lead_count,			
			t2.name AS source_name 
			FROM dashboard_day_wise_source_report AS t1 
			INNER JOIN source AS t2 ON t1.source_id=t2.id
			WHERE 1=1 $subsql GROUP BY source_id ORDER BY SUM(total_lead_count) DESC LIMIT $start,$limit";
		//echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_lead_lost_reason_vs_lead_source_report($argument)
	{
		$subsql='';
		$start=$argument['start'];
		$limit=$argument['limit'];
		$date_str='';
		

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}

		if($argument['filter_source_id']!='')
		{			
			$subsql.=" AND source_id='".$argument['filter_source_id']."'";
		}


		$sql="SELECT t1.reason_id,
			SUM(t1.total_lead_count) AS total_lead_count,
			t2.name AS reason_name 
			FROM dashboard_day_wise_lost_reason_report AS t1 
			INNER JOIN opportunity_regret_reason AS t2 ON t1.reason_id=t2.id
			WHERE 1=1 $subsql GROUP BY reason_id ORDER BY SUM(total_lead_count) DESC LIMIT $start,$limit";
		//echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_daily_sales_report_count($argument)
	{
		$subsql='';		
		if($argument['filter_daily_sales_report_group_by']!='')
		{			
			if(strtoupper($argument['filter_daily_sales_report_group_by'])=='D')
			{
				$group_by=" GROUP BY date";
			}
			else if(strtoupper($argument['filter_daily_sales_report_group_by'])=='W')
			{
				$group_by=" GROUP BY week(date)";
			}
			else if(strtoupper($argument['filter_daily_sales_report_group_by'])=='M')
			{
				$group_by=" GROUP BY month(date)";
			}				
		}

		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}
		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}
		$sql="SELECT id FROM dashboard_day_wise_sales_report WHERE 1=1 $subsql $group_by";
		$query = $this->client_db->query($sql,false);  
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
		
	}
	
	public function get_daily_sales_report($argument)
	{
		$subsql='';
		$start=$argument['start'];
		$limit=$argument['limit'];
		$date_str='';
		if($argument['filter_daily_sales_report_group_by']!='')
		{			
			if(strtoupper($argument['filter_daily_sales_report_group_by'])=='D')
			{
				$group_by=" GROUP BY date";
				$date_str="date";
			}
			else if(strtoupper($argument['filter_daily_sales_report_group_by'])=='W')
			{
				$group_by=" GROUP BY week(date)";
				$date_str="CONCAT('Week:',WEEK(date),' (',YEAR(date),')')";
			}
			else if(strtoupper($argument['filter_daily_sales_report_group_by'])=='M')
			{
				$group_by=" GROUP BY month(date)";
				$date_str="CONCAT(MONTHNAME(date),' (',YEAR(date),')')";
			}				
		}


		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($argument['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($argument['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($argument['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($argument['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($argument['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}

			if($argument['filter_date_range_pre_define']!='TILLDATE')
			{
				$start_date = date("Y-m-d",strtotime("-$last_day days", strtotime($current_date)));
				$end_date=date("Y-m-d",strtotime($current_date));			
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}

		$sql="SELECT 
			SUM(new_lead_count) AS total_new_lead_count,
			SUM(updated_lead_count) AS total_updated_lead_count,
			SUM(quoted_lead_count) AS total_quoted_lead_count,
			SUM(pending_lead_count) AS total_pending_lead_count,
			SUM(regretted_lead_count) AS total_regretted_lead_count,
			SUM(deal_lost_lead_count) AS total_deal_lost_lead_count,
			SUM(deal_won_lead_count) AS total_deal_won_lead_count,
			SUM(revenue) AS total_revenue,
			GROUP_CONCAT(revenue_wise_currency SEPARATOR '@') AS total_revenue_wise_currency,
			SUM(auto_regretted_lead_count) AS total_auto_regretted_lead_count,
			SUM(auto_deal_lost_lead_count) AS total_auto_deal_lost_lead_count,
			$date_str AS date_str FROM dashboard_day_wise_sales_report 
			WHERE 1=1 $subsql $group_by ORDER BY date DESC LIMIT $start,$limit";
			// echo $sql;die();
		$query = $this->client_db->query($sql,false);  
		//return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_lead_vs_order_report($argument)
	{
		$subsql='';
		// $start=$argument['start'];
		// $limit=$argument['limit'];
		$limit_str='';
		$date_str='';
		if($argument['filter_daily_sales_report_group_by']!='')
		{			
			if(strtoupper($argument['filter_daily_sales_report_group_by'])=='D')
			{
				$group_by=" GROUP BY date";
				$date_str="date";
			}
			else if(strtoupper($argument['filter_daily_sales_report_group_by'])=='W')
			{
				$group_by=" GROUP BY week(date)";
				$date_str="CONCAT('Week:',WEEK(date),' (',YEAR(date),')')";
			}
			else if(strtoupper($argument['filter_daily_sales_report_group_by'])=='M')
			{
				$group_by=" GROUP BY month(date)";
				$date_str="CONCAT(MONTHNAME(date),' (',YEAR(date),')')";
			}				
		}


		if($argument['filter_selected_user_id']!='')
		{			
			$subsql.=" AND assigned_user_id IN (".$argument['filter_selected_user_id'].")";
		}

		if($argument['filter_date_range_pre_define']!='' && $argument['filter_date_range_pre_define']>0)
		{			
			

			$current_date=date("Y-m-d");
			$last_x_month=$argument['filter_date_range_pre_define'];
			

			$start_date = date("Y-m-d", strtotime("+$last_x_month month", $current_date));
			$end_date=date("Y-m-d",strtotime($current_date));
			$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			$limit_str .=" LIMIT 0,$last_x_month";
			
		}
		else
		{
			if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
				$subsql.=" AND (date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}

		$sql="SELECT 
			SUM(new_lead_count) AS total_lead_count,
			SUM(deal_won_lead_count) AS total_deal_won_lead_count,			
			$date_str AS date_str FROM dashboard_day_wise_sales_report 
			WHERE 1=1 $subsql $group_by 
			ORDER BY date DESC $limit_str";
		// echo $sql; die();
		$query = $this->client_db->query($sql,false);  
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
		
	}
}

?>