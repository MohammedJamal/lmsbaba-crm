<?php
class Dashboard_v2_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $is_manager = 'N';
	private $manager_id_arr = array();
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
		if($result){
			if($result->num_rows())
			{
				$active_lead_count_str='';
				$active_lead_ids='';
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
		$total_lead_count=0;
		$active_lead_count=0;
		$new_lead_count=0;
		$today_followup_count=0;
		$pending_followup_count=0;
		$upcoming_followup_count=0;
		$quoted_lead_count=0;
		$auto_regretted_lead_count=0;
		$foreign_lead_count=0;
		$domestic_lead_count=0;
		if($query_q)
		{
			if($query_q->num_rows() > 0) 
			{			
				foreach($query_q->result() AS $row_q)
				{
					$quoted_lead_count++;
				}
			}
		}
		
		if($query)
		{
			if($query->num_rows() > 0) 
			{
				//$this->client_db->last_query();
				foreach($query->result() AS $row)
				{
					$total_lead_count=$total_lead_count+$row->lead_count;
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
				// $auto_regretted_lead_count_per=(int)(($auto_regretted_lead_count/$active_lead_count)*100);
				$auto_regretted_lead_count_per=(int)(($auto_regretted_lead_count/$total_lead_count)*100);
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
		
		

		return $return_arr;
	}
	
	public function get_this_month_data_xxx($arg)
	{

		if($arg['filter_selected_user_id']!='')
		{		
			$subsql=" AND assigned_user_id IN (".$arg['filter_selected_user_id'].")";			
		}

		// ===================== START LEAD PART ====================================
		$sql="SELECT id FROM lead WHERE MONTH(create_date)='".$arg['cur_month']."' AND YEAR(create_date)='".$arg['cur_year']."' ".$subsql." ";
		$cur_lead = $this->client_db->query($sql,false);
		$cur_lead_qty = $cur_lead->num_rows();
		
		$sql="SELECT id FROM lead WHERE MONTH(create_date)='".$arg['pre_month']."' AND YEAR(create_date)='".$arg['pre_year']."' ".$subsql." ";
		$pre_lead = $this->client_db->query($sql,false);
		$pre_lead_qty = $pre_lead->num_rows();
		

		if($cur_lead_qty>$pre_lead_qty)
		{
			$p=$cur_lead_qty-$pre_lead_qty;
			$since_new_leads=($p*100)/$cur_lead_qty;
			$newlead_growth_type= 'P'; //Prpfit 
		} else {
			$l=$pre_lead_qty-$cur_lead_qty;
			$since_new_leads=($l*100)/$pre_lead_qty;
			$newlead_growth_type= 'L'; //Loss
		}

		$leads_data = array(
			'new_leads' => $cur_lead_qty,
			'since_new_leads' => $since_new_leads,
			'newlead_growth_type' => $newlead_growth_type
		);

		// ===================== END LEAD PART ====================================

		// ===================== START REVENUE PART =================================
		$revenue_data = array(
			'revenue' => '269000',
			'since_revenue' => '7.00',
			'revenue_growth_type' => $newlead_growth_type
		);
		// ===================== END REVENUE PART ====================================

		// ===================== START SALES ORDER PART ====================================
		$sales_data = array(
			'sales_order' => '3',
			'since_sales_order' => '1.08',
			'sales_growth_type' => $newlead_growth_type
		);
		// ===================== END SALES ORDER PART ====================================

		// ===================== START NEW CUSTOMER PART ====================================
		$customer_data = array(
			'customers' => '4',
			'since_customers' => '4.87',
			'customer_growth_type' => $newlead_growth_type
		);
		// ===================== END NEW CUSTOMER PART ====================================

		$data = array(
			'leads_data' => $leads_data,
			'revenue_data' => $revenue_data,
			'sales_data' => $sales_data,
			'customer_data' => $customer_data		
		);  
		return $data;
	}

	public function get_this_month_data($arg)
	{
		$subsql="";
		if($arg['filter_selected_user_id']!='')
		{		
			$subsql .=" AND assigned_user_id IN (".$arg['filter_selected_user_id'].")";			
		}
		$sql="SELECT DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m') as this_month,
		DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m') as previous_month,
		'new_customer' as type,SUM(CASE WHEN (DATE_FORMAT(first_date_po,'%Y%m') = DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m')) THEN 
			1 ELSE 0 END) as total_current_month,SUM(CASE WHEN (DATE_FORMAT(first_date_po,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m')) THEN 
			1 ELSE 0 END) as total_prev_month	
		FROM 
		(
			SELECT 
			t1.id,
			min(t4.po_date) as first_date_po
			FROM customer as t1 
			JOIN lead as t2 on t1.id = t2.customer_id
			JOIN lead_opportunity as t3 on t2.id = t3.lead_id
			JOIN lead_opportunity_wise_po as t4 on t3.id = t4.lead_opportunity_id
			JOIN (select @var_month:=".$arg['selected_month'].",@var_year:=".$arg['selected_year'].") as v on 1=1
			WHERE t2.assigned_user_id in(".$arg['filter_selected_user_id'].")
			GROUP BY t1.id
		) AS cs
		UNION 
			SELECT 
			DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m') as this_month,
			DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m') as previous_month,
			'sales_order' as type,
			SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m')) THEN 
				1 ELSE 0 END) as total_current_month,
			SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m')) THEN 
				1 ELSE 0 END) as total_prev_month	
			FROM lead_opportunity_wise_po as t1 
			JOIN lead_opportunity as t2 on t2.id = t1.lead_opportunity_id
			JOIN lead as ld on ld.id = t2.lead_id 
			JOIN (select @var_month:=".$arg['selected_month'].",@var_year:=".$arg['selected_year'].") as v on 1=1
			WHERE t1.is_cancel='N' and ld.assigned_user_id in(".$arg['filter_selected_user_id'].")
		UNION
			SELECT DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m') as this_month,
			DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m') as previous_month,
			'revenue' as type,
			ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m')) THEN 
				coalesce(t2.deal_value_as_per_purchase_order,0) ELSE 0 END)) as total_current_month,
			ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m')) THEN 
				coalesce(t2.deal_value_as_per_purchase_order,0) ELSE 0 END)) as total_prev_month	
			FROM lead_opportunity_wise_po as t1
			JOIN lead_opportunity as t2 on t1.lead_opportunity_id = t2.id			
			JOIN (select @var_month:=".$arg['selected_month'].",@var_year:=".$arg['selected_year'].") as v on 1=1
			JOIN lead as ld on ld.id = t2.lead_id 
			WHERE t1.is_cancel='N' and ld.assigned_user_id in(".$arg['filter_selected_user_id'].")
		UNION 
		SELECT 
		DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m') as this_month,
		DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m') as previous_month,
		'new_lead' as type,
		SUM(CASE WHEN (DATE_FORMAT(t1.create_date,'%Y%m') = DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m')) THEN 
			1 ELSE 0 END) as total_current_month,
		SUM(CASE WHEN (DATE_FORMAT(t1.create_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m')) THEN 
			1 ELSE 0 END) as total_prev_month	
		FROM lead as t1		
		JOIN (select @var_month:=".$arg['selected_month'].",@var_year:=".$arg['selected_year'].") as v on 1=1
		WHERE t1.assigned_user_id IN (".$arg['filter_selected_user_id'].")";
		// echo $sql; die();
		$query = $this->client_db->query($sql,false); 

		if($query){
			$result = $query->result_array();
			$new_customer = $result[0];
			$sales_order = $result[1];
			$revenue = $result[2];
			$new_lead = $result[3];
		} else {
			return array();
		}


		// ===================== START LEAD PART ====================================
		
		if($new_lead['total_current_month']>$new_lead['total_prev_month'])
		{
			$p=$new_lead['total_current_month']-$new_lead['total_prev_month'];
			$since_new_leads=($p*100)/$new_lead['total_current_month'];
			$newlead_growth_type= 'P'; //Prpfit 
		} else {
			$l=$new_lead['total_prev_month']-$new_lead['total_current_month'];
			if($new_lead['total_prev_month']>0){
				$since_new_leads=($l*100)/$new_lead['total_prev_month'];
			} else {
				$since_new_leads=0;
			}
			$newlead_growth_type= 'L'; //Loss
		}

		$leads_data = array(
			'new_leads' => $new_lead['total_current_month'],
			'since_new_leads' => $since_new_leads,
			'newlead_growth_type' => $newlead_growth_type
		);

		// ===================== END LEAD PART ====================================

		// ===================== START REVENUE PART =================================
		if($revenue['total_current_month']>$revenue['total_prev_month'])
		{
			$p=$revenue['total_current_month']-$revenue['total_prev_month'];
			$since_revenue=($p*100)/$revenue['total_current_month'];
			$revenue_growth_type= 'P'; //Prpfit 
		} else {
			$l=$revenue['total_prev_month']-$revenue['total_current_month'];
			if($revenue['total_prev_month']>0){
				$since_revenue=($l*100)/$revenue['total_prev_month'];
			} else {
				$since_revenue=0;
			}
			$revenue_growth_type= 'L'; //Loss
		}

		$revenue_data = array(
			'revenue' => $revenue['total_current_month'],
			'since_revenue' => $since_revenue,
			'revenue_growth_type' => $revenue_growth_type
		);
		// ===================== END REVENUE PART ====================================

		// ===================== START SALES ORDER PART ====================================
		if($sales_order['total_current_month']>$sales_order['total_prev_month'])
		{
			$p=$sales_order['total_current_month']-$sales_order['total_prev_month'];
			$since_sales_order=($p*100)/$sales_order['total_current_month'];
			$sales_order_growth_type= 'P'; //Prpfit 
		} else {
			$l=$sales_order['total_prev_month']-$sales_order['total_current_month'];
			if($sales_order['total_prev_month']>0){
				$since_sales_order=($l*100)/$sales_order['total_prev_month'];
			} else {
				$since_sales_order=0;
			}
			$sales_order_growth_type= 'L'; //Loss
		}
		
		$sales_data = array(
			'sales_order' => $sales_order['total_current_month'],
			'since_sales_order' => $since_sales_order,
			'sales_growth_type' => $sales_order_growth_type
		);
		// ===================== END SALES ORDER PART ====================================

		// ===================== START NEW CUSTOMER PART ====================================

		if($new_customer['total_current_month']>$new_customer['total_prev_month'])
		{
			$p=$new_customer['total_current_month']-$new_customer['total_prev_month'];
			$since_new_customer=($p*100)/$new_customer['total_current_month'];
			$new_customer_growth_type= 'P'; //Prpfit 
		} else {
			$l=$new_customer['total_prev_month']-$new_customer['total_current_month'];
			if($new_customer['total_prev_month']>0){
				$since_new_customer=($l*100)/$new_customer['total_prev_month'];
			} else {
				$since_new_customer=0;
			}
			$new_customer_growth_type= 'L'; //Loss
		}
		
		$customer_data = array(
			'customers' => $new_customer['total_current_month'],
			'since_customers' => $since_new_customer,
			'customer_growth_type' => $new_customer_growth_type
		);
		// ===================== END NEW CUSTOMER PART ====================================

		$data = array(
			'leads_data' => $leads_data,
			'revenue_data' => $revenue_data,
			'sales_data' => $sales_data,
			'customer_data' => $customer_data		
		);  

		// echo'<pre>';
		// print_r($data);
		// die;
		return $data;

	}

	public function get_lead_pipeline_data($arg)
	{
			
		$sql="SELECT * FROM 
			(
				SELECT t1.id,t1.name,t1.sort,coalesce(t2.total_lead,0) as total_lead,if(t1.id in (3,4,5,6,7),1,0) as inactive_stage,@won:=if(t1.id = 4 OR @won != 0,sort,NULL) as won_pos
				FROM opportunity_stage as t1 
				LEFT JOIN 
				(
					SELECT last_stage_id,count(lead_id) as total_lead
					FROM 
					(
						SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
						FROM 
						(
							SELECT t1.id,t1.lead_id,t1.stage_id,t2.name,t2.sort,t3.total_stage
							FROM `lead_stage_log` as t1 
							JOIN (SELECT * FROM `opportunity_stage` WHERE id NOT IN (3,4,5,6,7) AND `is_active_lead`='Y' AND `is_deleted` = 'N' ORDER BY sort) AS t2 ON t1.stage_id = t2.id
							JOIN lead as ld on ld.id = t1.lead_id 
							JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
							JOIN (SELECT lead_id,count(stage_id) AS total_stage,max(if(stage_id in (3,4,5,6,7),1,0)) inactive from lead_stage_log  group by lead_id having inactive=0) as t3 ON t1.lead_id = t3.lead_id
							WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") ORDER BY `lead_id`,t2.sort,t2.id
						) AS t
					) AS t1				
					WHERE last_stage_id IS NOT null	GROUP BY last_stage_id
				) AS t2 on t1.id = t2.last_stage_id	WHERE 1=1 AND t1.`is_active_lead`='Y' AND t1.`is_deleted` = 'N' ORDER BY t1.sort,t1.id
			) AS t1	WHERE won_pos IS null AND inactive_stage=0	ORDER BY sort";
		// echo $sql; die();
		$query = $this->client_db->query($sql,false);
		if($query){
			return $query->result_array();
		} else {
			return array();
		}		
	}

	public function get_opportunity_data($arg)
	{ 
		$total_lead_sql="SELECT count(t1.lead_id) AS total_lead_count,SUM(if(t1.is_not_open=0,1,0)) AS total_open_lead_count
						FROM (
								SELECT ls.lead_id,MAX(IF(ls.stage_id IN(3, 4, 5, 6, 7),1,0)) AS is_not_open  
								FROM `lead` AS ld
								JOIN lead_stage_log AS ls
								ON ld.id = ls.lead_id
								LEFT JOIN lead_opportunity AS lo ON lo.lead_id = ld.id 
								WHERE ld.assigned_user_id IN(".$arg['filter_selected_user_id'].") GROUP BY  ld.id 
							) AS t1";
		$total_lead_query = $this->client_db->query($total_lead_sql,false);
		if($total_lead_query){
			$total_lead_result=$total_lead_query->row_array();
			$total_lead = $total_lead_result['total_lead_count'];
			$total_open_lead = $total_lead_result['total_open_lead_count'];
		} 
		else {
			$total_lead = 0;
			$total_open_lead =0;
		} 		

		$sql = "SELECT 'open_opportunity' AS type,
				round(sum(if(lo.deal_value is null,coalesce(t1.deal_value,0),coalesce(lo.deal_value,0)))) as total_opportunity,
				count(if(lo.deal_value is not null or lo.deal_value is not null ,ld.id,null)) as total_opportunity_lead
				FROM lead AS ld 
				JOIN (SELECT ls.lead_id,max(if(ls.stage_id IN (3,4,5,6,7),1,0)) AS is_active,max(lo.id) as latest_lead_opportunity_id,max(if(lo.status=4,1,0)) as is_matured,ld.deal_value
						FROM `lead` as ld 
						JOIN lead_stage_log as ls on ld.id = ls.lead_id 
						LEFT JOIN lead_opportunity as lo on lo.lead_id = ld.id
						WHERE ld.assigned_user_id in(".$arg['filter_selected_user_id'].")
						GROUP BY ld.id HAVING is_active!=1 AND is_matured=0
					) AS t1 on ld.id = t1.lead_id 
				LEFT JOIN lead_opportunity as lo ON lo.lead_id = t1.lead_id and lo.id = t1.latest_lead_opportunity_id		
				UNION		
				SELECT 'business_opportunity' as type,round(sum(coalesce(lo.deal_value_as_per_purchase_order,0))) as total_opportunity,count(lo.lead_id) as total_opportunity_lead
				FROM `lead` as ld 
				JOIN lead_opportunity as lo on lo.lead_id = ld.id WHERE ld.assigned_user_id in(".$arg['filter_selected_user_id'].") and lo.status=4				
				UNION				
				SELECT 'lost_opportunity' as type,
				round(sum(lo.deal_value)) as total_opportunity,
				count(lo.lead_id) as total_opportunity_lead
				FROM lead_opportunity as lo
				JOIN (
					SELECT ls.lead_id,max(if(ls.stage_id = 5,1,0)) as is_lost,max(lo.id) as latest_lead_opportunity_id
					FROM `lead` as ld 
					JOIN lead_stage_log as ls on ld.id = ls.lead_id 
					JOIN lead_opportunity as lo on lo.lead_id = ld.id
					where ld.assigned_user_id in(".$arg['filter_selected_user_id'].")
					group by ld.id 
					having is_lost=1 
				) AS t1 on lo.lead_id = t1.lead_id AND lo.id = t1.latest_lead_opportunity_id";
		// echo $sql; die();
		$query = $this->client_db->query($sql,false); 
		if($query){
			$result=$query->result_array();
			$open_opportunity_percentage = round(($result[0]['total_opportunity_lead']/$total_open_lead)*100);
			$business_conversion_percentage = round(($result[1]['total_opportunity_lead']/$total_lead)*100);
			$opportunity_lost_percentage = round(($result[2]['total_opportunity_lead']/$total_lead)*100);
			$data = array(
				'open_opportunity_value' => $result[0]['total_opportunity'],
				'open_opportunity_percentage' => $open_opportunity_percentage,
				'business_conversion_value' => $result[1]['total_opportunity'],
				'business_conversion_percentage' => $business_conversion_percentage,
				'opportunity_lost_value' => $result[2]['total_opportunity'],
				'opportunity_lost_percentage' => $opportunity_lost_percentage			
			);
		}
		else{
			$data = array(
				'open_opportunity_value' => '0',
				'open_opportunity_percentage' => '0',
				'business_conversion_value' => '0',
				'business_conversion_percentage' => '0',
				'opportunity_lost_value' => '0',
				'opportunity_lost_percentage' => '0'			
			);
		}
		return $data;
	}

	public function get_financial_review($arg)
	{
		$sql = "SELECT 
		'financial_review' as type
	   ,DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m') as month_1
	   ,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m') as month_2
	   ,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 2 MONTH),'%Y%m') as month_3
	   ,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 3 MONTH),'%Y%m') as month_4
	   ,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 4 MONTH),'%Y%m') as month_5
	   ,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 5 MONTH),'%Y%m') as month_6
	   
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m')) THEN 
	   coalesce(t2.deal_value_as_per_purchase_order,0) ELSE 0 END)) as total_month_1
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m')) THEN 
	   coalesce(t2.deal_value_as_per_purchase_order,0) ELSE 0 END)) as total_month_2	
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 2 MONTH),'%Y%m')) THEN 
	   coalesce(t2.deal_value_as_per_purchase_order,0) ELSE 0 END)) as total_month_3	
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 3 MONTH),'%Y%m')) THEN 
	   coalesce(t2.deal_value_as_per_purchase_order,0) ELSE 0 END)) as total_month_4	
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 4 MONTH),'%Y%m')) THEN 
	   coalesce(t2.deal_value_as_per_purchase_order,0) ELSE 0 END)) as total_month_5	
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 5 MONTH),'%Y%m')) THEN 
	   coalesce(t2.deal_value_as_per_purchase_order,0) ELSE 0 END)) as total_month_6	
	   from lead_opportunity_wise_po as t1
	   join lead_opportunity as t2 on t1.lead_opportunity_id = t2.id			
	   join (select @var_month:=MONTH(CURRENT_DATE()),@var_year:=YEAR(CURRENT_DATE())) as v on 1=1
	   join lead as ld on ld.id = t2.lead_id 
		WHERE t1.is_cancel='N' and ld.assigned_user_id in(".$arg['filter_selected_user_id'].")";
		// echo $sql;die();
		$query = $this->client_db->query($sql,false); 
		if($query){
			return $query->row_array();

		} else {
			return array();
		}
	}
	
	public function get_sales_orders($arg)
	{
		$sql = "SELECT 
		'financial_review' as type
	   ,DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m') as month_1
	   ,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m') as month_2
	   ,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 2 MONTH),'%Y%m') as month_3
	   ,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 3 MONTH),'%Y%m') as month_4
	   ,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 4 MONTH),'%Y%m') as month_5
	   ,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 5 MONTH),'%Y%m') as month_6
	   
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m')) THEN 
	   1 ELSE 0 END)) as total_month_1
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m')) THEN 
	   1 ELSE 0 END)) as total_month_2	
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 2 MONTH),'%Y%m')) THEN 
	   1 ELSE 0 END)) as total_month_3	
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 3 MONTH),'%Y%m')) THEN 
	   1 ELSE 0 END)) as total_month_4	
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 4 MONTH),'%Y%m')) THEN 
	   1 ELSE 0 END)) as total_month_5	
	   ,ROUND(SUM(CASE WHEN (DATE_FORMAT(t1.po_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 5 MONTH),'%Y%m')) THEN 
	   1 ELSE 0 END)) as total_month_6	
		   from lead_opportunity_wise_po as t1 
		   join lead_opportunity as t2 on t2.id = t1.lead_opportunity_id
		   join lead as ld on ld.id = t2.lead_id 
		   join (select @var_month:=MONTH(CURRENT_DATE()),@var_year:=YEAR(CURRENT_DATE())) as v on 1=1
		   WHERE t1.is_cancel='N' and ld.assigned_user_id in(".$arg['filter_selected_user_id'].")";
		$query = $this->client_db->query($sql,false); 

		if($query){
			return $query->result_array();

		} else {
			return array();
		}
	}

	public function get_leads_opportunity($arg)
	{
		$sql = "select 
		'lead_opportunity' as type
			,DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m') as month_1
			,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m') as month_2
			,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 2 MONTH),'%Y%m') as month_3
			,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 3 MONTH),'%Y%m') as month_4
			,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 4 MONTH),'%Y%m') as month_5
			,DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 5 MONTH),'%Y%m') as month_6
			
			,COUNT(DISTINCT(CASE WHEN (DATE_FORMAT(lo.create_date,'%Y%m') = DATE_FORMAT(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01'),'%Y%m')) THEN 
			lo.id ELSE NULL END)) as total_month_1
			,COUNT(DISTINCT(CASE WHEN (DATE_FORMAT(lo.create_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 1 MONTH),'%Y%m')) THEN 
			lo.id ELSE NULL END)) as total_month_2	
			,COUNT(DISTINCT(CASE WHEN (DATE_FORMAT(lo.create_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 2 MONTH),'%Y%m')) THEN 
			lo.id ELSE NULL END)) as total_month_3	
			,COUNT(DISTINCT(CASE WHEN (DATE_FORMAT(lo.create_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 3 MONTH),'%Y%m')) THEN 
			lo.id ELSE NULL END)) as total_month_4	
			,COUNT(DISTINCT(CASE WHEN (DATE_FORMAT(lo.create_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 4 MONTH),'%Y%m')) THEN 
			lo.id ELSE NULL END)) as total_month_5	
			,COUNT(DISTINCT(CASE WHEN (DATE_FORMAT(lo.create_date,'%Y%m') = DATE_FORMAT(LAST_DAY(concat(lpad(@var_year,4,0),'-',lpad(@var_month,2,0),'-01') - INTERVAL 5 MONTH),'%Y%m')) THEN 
			lo.id ELSE NULL END)) as total_month_6
		from lead as lo 
		join (select @var_month:=MONTH(CURRENT_DATE()),@var_year:=YEAR(CURRENT_DATE())) as v on 1=1 
		where lo.assigned_user_id in(".$arg['filter_selected_user_id'].")";//echo $sql; die();
		$query = $this->client_db->query($sql,false); 

		if($query){
			return $query->result_array();

		} else {
			return array();
		}
	}

	public function get_user_wise_sales_pipeline($arg)
	{		
		$db_name=$arg['db_name'];
		$filter_selected_user_id=$arg['filter_selected_user_id'];
		$sql="call u412811690_lmsbaba.sp_user_staging_pipeline('".$db_name."','".$filter_selected_user_id."')";
		$query = $this->client_db->query($sql); 
		if($query){
			return $query->result_array();

		} else {
			return array();
		}
	}

	

	public function get_user_activity_report_v2($arg)
	{

		// $start=$arg['start'];
		// $limit=$arg['limit'];

		// if(trim($start)!=''){
		// 	$limitcond="LIMIT ".$start.",".$limit;
		// } else {
		// 	$limitcond="";
		// }
		
		if($arg['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($arg['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($arg['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
			}
			if($arg['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($arg['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($arg['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}
			
			if($arg['filter_date_range_pre_define']!='TILLDATE')
			{				
				$datarange_ld=" AND ld.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_lc=" AND DATE(lc.call_start) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_m=" AND date(m.checkin_datetime) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_cm=" AND cm.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_lo=" AND lo.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_ls=" AND date(ls.create_datetime) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_po=" AND po.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_common=" AND create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_common_datetime=" AND DATE(create_datetime) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
			}
			
		}
		else
		{
			if($arg['filter_date_range_user_define_from']!='' && $arg['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($arg['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($arg['filter_date_range_user_define_to']);
				$datarange_ld=" AND (ld.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_lc=" AND (DATE(lc.call_start) BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_m=" AND (date(m.checkin_datetime) BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_cm=" AND (cm.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_lo=" AND (lo.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_ls=" AND (date(ls.create_datetime) BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_po=" AND (po.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_common=" AND (DATE(create_date) BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_common_datetime=" AND (DATE(create_datetime) BETWEEN '".$start_date."' AND '".$end_date."')";
				
			}
		}
		
		$sql="SELECT t2.id as user_id,
			t2.name,
			t2.photo,
			designation.name AS designation,
			max(if(t1.type='new_lead',coalesce(t3.total_lead,0),0)) as total_new_lead,
			max(if(t1.type='call_log',coalesce(t3.total_lead,0),0)) as total_call_log,
			max(if(t1.type='meeting',coalesce(t3.total_lead,0),0)) as total_meeting,
			max(if(t1.type='updated',coalesce(t3.total_lead,0),0)) as total_updated,
			max(if(t1.type='quoted',coalesce(t3.total_lead,0),0)) as total_quoted,
			max(if(t1.type='dealwon',coalesce(t3.total_lead,0),0)) as total_dealwon,
			max(if(t1.type='lost',coalesce(t3.total_lead,0),0)) as total_lost,
			max(if(t1.type='revenue',coalesce(t3.total_lead,0),0)) as total_revenue
			FROM user AS t2 
			INNER JOIN designation ON t2.designation_id=designation.id
			JOIN (
				select 'new_lead' as type 
				union select 'call_log' as type 
				union select 'meeting' as type 
				union select 'updated' as type 
				union select 'quoted' as type 
				union select 'dealwon' as type 
				union select 'lost' as type 
				union select 'revenue' as type 
			) AS t1 on 1=1
			LEFT JOIN 
			(	SELECT 'new_lead' as type,ld.assigned_user_id,count(ld.id) as total_lead FROM lead AS ld 
				WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $datarange_ld GROUP by ld.assigned_user_id
				UNION 
				SELECT 'call_log' as type,lc.user_id as assigned_user_id,count(lc.id) as total_lead	
				FROM tbl_call_history_for_lead_tmp AS lc WHERE lc.status!=1 AND lc.user_id IN (".$arg['filter_selected_user_id'].") $datarange_lc
				GROUP by lc.user_id
				UNION 
				SELECT 'meeting' as type,m.user_id as assigned_user_id,count(m.id) as total_lead FROM  meeting AS m 
				WHERE m.user_id IN (".$arg['filter_selected_user_id'].") AND m.status_id='3' $datarange_m GROUP by m.user_id
				UNION 	
				
				SELECT 
					'updated' as type,cm.user_id as assigned_user_id,count(distinct cm.lead_id) as total_lead					
					from  lead_comment as cm 
					join (
						select lead_id,count(lead_id) as total_comment
						from lead_comment 
						where user_id IN (".$arg['filter_selected_user_id'].") AND (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%') $datarange_common 
						group by lead_id  
						HAVING total_comment>=1
						) as lc on cm.lead_id = lc.lead_id
					where cm.user_id IN (".$arg['filter_selected_user_id'].") $datarange_cm GROUP by cm.user_id

				
				UNION 
				SELECT 'quoted' as type,ld.assigned_user_id,count(if(ls.stage_id =2,ls.lead_id,NULL)) as total_lead	
				FROM lead_stage_log as ls JOIN lead AS ld ON ld.id = ls.lead_id 
				WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $datarange_common_datetime GROUP by ld.assigned_user_id					
				UNION
				SELECT 'dealwon' as type,ld.assigned_user_id,count(if(ls.stage_id =4,ls.lead_id,NULL)) as total_lead 
				FROM lead_stage_log as ls JOIN lead as ld on ld.id = ls.lead_id	
				WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $datarange_common_datetime GROUP by ld.assigned_user_id				
				UNION 
				SELECT 'lost' as type,ld.assigned_user_id,count(t1.lead_id) as total_opportunity_lead
				FROM `lead` as ld
				JOIN (
					SELECT ls.lead_id,
                    max(if(ls.stage_id = 3 || ls.stage_id = 5||ls.stage_id = 7 ,1,0)) as is_lost,
                    date(ls.create_datetime) AS ls_cd
					FROM `lead` as ld 
					left JOIN lead_stage_log as ls on ld.id = ls.lead_id 					
					WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") AND ls.stage_id IN (3,5,7) $datarange_ls
					GROUP BY ld.id  HAVING is_lost=1 
				) AS t1 on ld.id = t1.lead_id GROUP by ld.assigned_user_id								
				UNION 					
				SELECT	'revenue' as type,
				ld.assigned_user_id,
				SUM(lo.deal_value_as_per_purchase_order) as total_opportunity_lead 
				FROM lead_opportunity as lo
				JOIN `lead` as ld on ld.id = lo.lead_id
				JOIN lead_opportunity_wise_po AS po ON lo.id = po.lead_opportunity_id
				WHERE po.is_cancel='N' AND ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $datarange_po GROUP by ld.assigned_user_id				
			) AS t3 on t2.id = t3.assigned_user_id and t1.type=t3.type
			WHERE  t2.id IN (".$arg['filter_selected_user_id'].") GROUP BY t2.id ORDER BY t2.id";
			// echo $sql; die();
		$query = $this->client_db->query($sql,false); 
		if($query){
			return $query->result_array();

		} else {
			return array();
		}

		// SELECT 'updated' as type,cm.user_id as assigned_user_id,count(distinct cm.lead_id) as total_lead FROM lead_comment AS cm 
		// 			JOIN (
		// 				SELECT lead_id,count(lead_id) as total_comment FROM lead_comment WHERE user_id IN (".$arg['filter_selected_user_id'].") $datarange_common 
		// 				GROUP BY lead_id HAVING total_comment>1
		// 				) AS lc ON cm.lead_id = lc.lead_id
		// 			WHERE cm.user_id IN (".$arg['filter_selected_user_id'].") $datarange_cm GROUP by cm.user_id
		// SELECT 'lost' as type,ld.assigned_user_id,count(lo.lead_id) as total_opportunity_lead FROM lead_opportunity as lo
		// 		JOIN `lead` as ld on ld.id = lo.lead_id
		// 			JOIN (
		// 				SELECT ls.lead_id,max(if(ls.stage_id = 5,1,0)) as is_lost,max(lo.id) as latest_lead_opportunity_id FROM `lead` as ld 
		// 				JOIN lead_stage_log as ls on ld.id = ls.lead_id 
		// 				JOIN lead_opportunity as lo on lo.lead_id = ld.id
		// 				WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $datarange_lo
		// 				GROUP BY ld.id HAVING is_lost=1 
		// 			) AS t1 ON lo.lead_id = t1.lead_id AND lo.id = t1.latest_lead_opportunity_id GROUP by ld.assigned_user_id

	}

	public function get_top_selling_produts($arg)
	{
		$subsql='';
		$limit_str='';
		if($arg['limit']){
			$limit_str= "LIMIT 0,".$arg['limit'];
		}

		if($arg['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($arg['filter_date_range_pre_define']=='CURRENTMONTH')
			{
				$subsql .=" AND po.po_date >= DATE_SUB(CURRENT_DATE, INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY)";
			}
			if($arg['filter_date_range_pre_define']=='LAST3MONTHS')
			{
				$subsql .=" AND po.po_date >= (CURRENT_DATE -INTERVAL 3 MONTH)";
			}
			if($arg['filter_date_range_pre_define']=='LAST6MONTHS')
			{
				$subsql .=" AND po.po_date >= (CURRENT_DATE -INTERVAL 6 MONTH)";
			}
			if($arg['filter_date_range_pre_define']=='LAST12MONTHS')
			{
				$subsql .=" AND po.po_date >= (CURRENT_DATE -INTERVAL 12 MONTH)";
			}
			// if($arg['filter_date_range_pre_define']=='TODAY')
			// {
			// 	$last_day=0;
			// 	$show_day=1;
			// }
			// if($arg['filter_date_range_pre_define']=='YESTERDAY')
			// {
			// 	$last_day=1;
			// 	$show_day=1;
			// }
			// if($arg['filter_date_range_pre_define']=='LAST7DAYS')
			// {
			// 	$last_day=7;
			// 	$show_day=7;
			// }
			// if($arg['filter_date_range_pre_define']=='LAST15DAYS')
			// {
			// 	$last_day=15;
			// 	$show_day=15;
			// }
			// if($arg['filter_date_range_pre_define']=='LAST30DAYS')
			// {
			// 	$last_day=30;
			// 	$show_day=30;
			// }
			
			// if($arg['filter_date_range_pre_define']!='TILLDATE')
			// {
			// 	$subsql .=" AND po.po_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
			// }			
		}
		else
		{
			if($arg['filter_date_range_user_define_from']!='' && $arg['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($arg['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($arg['filter_date_range_user_define_to']);
				$subsql .=" AND (po.po_date BETWEEN '".$start_date."' AND '".$end_date."')";
			}
		}
		$sql = "SELECT qp.product_id,pv.name,
		count(q.id) as total_quotation,
		round(sum(if(q.currency_type='USD',qp.quantity*qp.price,0))) as total_revenue_usd,
        round(sum(if(q.currency_type!='USD',qp.quantity*qp.price,0))) as total_revenue_inr,
		round(sum(qp.quantity*qp.price)) as total_revenue,
		count(po.id) as total_po
		FROM `quotation` as q 
		join quotation_product as qp on q.id = qp.quotation_id 
		join lead_opportunity as lo on lo.id = q.opportunity_id
		left join lead_opportunity_wise_po as po on po.lead_opportunity_id = lo.id
		join product_varient as pv on pv.id = qp.product_id 
		JOIN lead as ld on ld.id = lo.lead_id 
		WHERE po.is_cancel='N' AND lo.status='4' AND ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $subsql 
		GROUP BY qp.product_id ORDER BY sum(qp.quantity*qp.price) DESC $limit_str";
		// echo $sql; die();
		$query = $this->client_db->query($sql,false); 
		if($query){
			return $query->result_array();
		} else {
			return array();
		}
	}

	public function get_latest_sales_orders($arg)
	{
		$limit_str='';
		if($arg['limit']){
			$limit_str= "LIMIT 0,".$arg['limit'];
		}
		$sql = "SELECT t1.po_date,t2.opportunity_title,t2.deal_value,t2.deal_value_as_per_purchase_order,user.name AS assigned_user,cust.company_name AS cust_company_name
				FROM lead_opportunity_wise_po as t1 
				JOIN lead_opportunity as t2 on t2.id = t1.lead_opportunity_id
				JOIN lead as ld on ld.id = t2.lead_id 
				INNER JOIN user ON ld.assigned_user_id=user.id 
				INNER JOIN customer AS cust ON ld.customer_id=cust.id
				WHERE t1.is_cancel='N' and ld.assigned_user_id in(".$arg['filter_selected_user_id'].") ORDER BY t1.po_date DESC $limit_str";
		
		$query = $this->client_db->query($sql,false); 
		if($query){
			return $query->result_array();

		} else {
			return array();
		}
	}

	


	public function get_daily_sales_report_v2($arg)
	{
		if($arg['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($arg['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
				$show_day=1;
			}
			if($arg['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
				$show_day=1;
				
				
			}
			if($arg['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
				$show_day=7;
			}
			if($arg['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
				$show_day=15;
			}
			if($arg['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
				$show_day=30;
			}
			
			if($arg['filter_date_range_pre_define']!='TILLDATE')
			{				
				$datarange_ld=" AND ld.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_lc=" AND DATE(lc.call_start) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_m=" AND date(m.checkin_datetime) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_cm=" AND cm.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				// $datarange_lo=" AND lo.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_ls=" AND date(ls.create_datetime) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_po=" AND po.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_common=" AND DATE(create_date) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				$datarange_common_datetime=" AND DATE(create_datetime) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";				
				$show_datarange=" WHERE autoid<=".$show_day;
				if($arg['filter_date_range_pre_define']!='YESTERDAY'){
					$show_datarange_end_date=DATE("Y-m-d");
				}				
				else{
					$show_datarange_end_date=date('Y-m-d',strtotime("-1 days"));
				}
			}
			
		}
		else
		{
			if($arg['filter_date_range_user_define_from']!='' && $arg['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($arg['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($arg['filter_date_range_user_define_to']);
				$datarange_ld=" AND (ld.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_lc=" AND (DATE(lc.call_start) BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_m=" AND (date(m.checkin_datetime) BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_cm=" AND (cm.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
				// $datarange_lo=" AND (lo.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_ls=" AND (date(ls.create_datetime) BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_po=" AND (po.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_common=" AND (DATE(create_date) BETWEEN '".$start_date."' AND '".$end_date."')";
				$datarange_common_datetime=" AND (DATE(create_datetime) BETWEEN '".$start_date."' AND '".$end_date."')";
				$show_datarange=" WHERE autoid<=(DATEDIFF('".$end_date."', '".$start_date."')+1)";
				$show_datarange_end_date=$end_date;	
			}
		}
		// echo $show_datarange_end_date;die();
		$sql="select t2.daily_date,
			  max(if(t1.type='new_lead',coalesce(t3.total_lead,0),0)) as total_new_lead,
			  max(if(t1.type='call_log',coalesce(t3.total_lead,0),0)) as total_call_log,
			  max(if(t1.type='meeting',coalesce(t3.total_lead,0),0)) as total_meeting,
			  max(if(t1.type='updated',coalesce(t3.total_lead,0),0)) as total_updated,
			  max(if(t1.type='quoted',coalesce(t3.total_lead,0),0)) as total_quoted,
			  max(if(t1.type='dealwon',coalesce(t3.total_lead,0),0)) as total_dealwon,
			  max(if(t1.type='lost',coalesce(t3.total_lead,0),0)) as total_lost,
			  max(if(t1.type='revenue',coalesce(t3.total_lead,0),0)) as total_revenue
			from 
			(
				select ('".$show_datarange_end_date."' -INTERVAL autoid-1 day) as daily_date from u412811690_lmsbaba.incr $show_datarange) as t2 
				join (
				select 'new_lead' as type 
				union select 'call_log' as type 
				union select 'meeting' as type 
				union select 'updated' as type 
				union select 'quoted' as type 
				union select 'dealwon' as type 
				union select 'lost' as type 
				union select 'revenue' as type 
				) as t1 on 1=1 
			
			left join 
			(				
				SELECT 'new_lead' as type,ld.create_date,count(ld.id) as total_lead
					FROM lead as ld	WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $datarange_ld GROUP by ld.create_date
				UNION 
				SELECT 'call_log' as type,DATE(lc.call_start) as create_date,count(lc.id) as total_lead
					FROM tbl_call_history_for_lead_tmp as lc WHERE lc.status != 1 AND lc.user_id IN (".$arg['filter_selected_user_id'].") $datarange_lc GROUP by DATE(lc.call_start)
				UNION 
				SELECT 'meeting' as type,date(m.checkin_datetime) as create_date,count(m.id) as total_lead
				FROM meeting as m WHERE m.user_id IN (".$arg['filter_selected_user_id'].") AND m.status_id='3' $datarange_m GROUP by date(m.checkin_datetime)
				UNION 
				
				SELECT 
					'updated' as type,DATE(lc.lc_cd) AS create_date,count(distinct cm.lead_id) as total_lead					
					FROM  lead_comment as cm 
					join (
						select lead_id,count(lead_id) as total_comment,DATE(create_date) AS lc_cd
						from lead_comment 
						where user_id IN (".$arg['filter_selected_user_id'].") AND (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%') $datarange_common 
						group by lead_id  
						HAVING total_comment>=1
						) as lc on cm.lead_id = lc.lead_id
					where cm.user_id IN (".$arg['filter_selected_user_id'].") $datarange_cm
					GROUP by DATE(lc.lc_cd)
				UNION 
				select 
					'quoted' as type,date(create_datetime) as create_date,count(if(ls.stage_id =2,ls.lead_id,NULL)) as total_lead
					from lead_stage_log as ls
					join lead as ld on ld.id = ls.lead_id
					WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $datarange_common_datetime
					GROUP by date(create_datetime)
					
				UNION
				select 
					'dealwon' as type,date(create_datetime) as create_date,count(if(ls.stage_id =4,ls.lead_id,NULL)) as total_lead
					from lead_stage_log as ls
					join lead as ld on ld.id = ls.lead_id
					WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $datarange_common_datetime
					GROUP by date(create_datetime)				
				UNION 
				SELECT 'lost' as type,t1.ls_cd AS create_date,count(t1.lead_id) as total_opportunity_lead
				FROM `lead` as ld
				JOIN (
					SELECT ls.lead_id,
                    max(if(ls.stage_id = 3 || ls.stage_id = 5||ls.stage_id = 7 ,1,0)) as is_lost,
                    date(ls.create_datetime) AS ls_cd
					FROM `lead` as ld 
					left JOIN lead_stage_log as ls on ld.id = ls.lead_id 					
					WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") AND ls.stage_id IN (3,5,7) $datarange_ls
					GROUP BY ld.id  HAVING is_lost=1 
				) AS t1 on ld.id = t1.lead_id GROUP by t1.ls_cd
				UNION 					
				select 'revenue' as type,po.create_date,
				SUM(lo.deal_value_as_per_purchase_order) as total_opportunity_lead
				from lead_opportunity as lo
				join `lead` as ld on ld.id = lo.lead_id
				join lead_opportunity_wise_po as po on lo.id = po.lead_opportunity_id
				where po.is_cancel='N' AND ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $datarange_po
				GROUP by po.create_date
			) as t3 ON t2.daily_date = t3.create_date AND t1.type=t3.type GROUP BY t2.daily_date ORDER BY t2.daily_date DESC";
			// echo $sql; die();		
		$query = $this->client_db->query($sql,false); 

		if($query){
			return $query->result_array();

		} else {
			return array();
		}
	}

	public function get_lead_by_source_report($arg)
	{
		if($arg['filter_date_range_pre_define']!='')
		{			
			$current_date=date("Y-m-d");
			if($arg['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
				$show_day=1;
			}
			if($arg['filter_date_range_pre_define']=='YESTERDAY')
			{
				// $last_day=1;
				// $show_day=1;
				$start_date=date('d.m.Y',strtotime("-1 days"));;
				$end_date=$start_date;
				$datarange_ld=" AND (ld.create_date BETWEEN '".$start_date."' AND '".$end_date."')";	
			}
			if($arg['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
				$show_day=7;
			}
			if($arg['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
				$show_day=15;
			}
			if($arg['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
				$show_day=30;
			}
			
			if(($arg['filter_date_range_pre_define']!='TILLDATE') && $arg['filter_date_range_pre_define']!='YESTERDAY')
			{				
				$datarange_ld=" AND ld.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
			}
			
		}
		else
		{
			if($arg['filter_date_range_user_define_from']!='' && $arg['filter_date_range_user_define_to']!='')
			{
				$start_date=date_display_format_to_db_format($arg['filter_date_range_user_define_from']);
				$end_date=date_display_format_to_db_format($arg['filter_date_range_user_define_to']);
				$datarange_ld=" AND (ld.create_date BETWEEN '".$start_date."' AND '".$end_date."')";				
			}
		}
		$sql="SELECT 
			t1.source_id,
			t1.source_name,
			COUNT(t1.lead_id) as total_lead,
			sum(is_active) as is_active,
			sum(is_won) as is_won,
			sum(is_lost) as is_lost,
			sum(is_quoted) as is_quoted,
			sum(USD_revenue) as USD_revenue,
			sum(INR_revenue) as INR_revenue,
			sum(total_orders) as total_orders 
			FROM 
			(
				SELECT sr.id as source_id,sr.name as source_name,ld.id as lead_id,
				max(if(lls.last_stage_id IN (3,4,5,6,7),0,1)) as is_active,
				max(if(lo.status=4,1,0)) as is_won,
				max(if(ls.stage_id = 5,1,0)) as is_lost,
				max(if(lls.last_stage_id =2,1,0)) as is_quoted,
				if(lo.currency_type='2',lo.deal_value_as_per_purchase_order,0) as USD_revenue,
				if(lo.currency_type='1',lo.deal_value_as_per_purchase_order,0) as INR_revenue,
				count(po.id) as total_orders 
				FROM `source` as sr 
				join lead as ld on sr.id = ld.source_id 
				join lead_stage_log as ls on ld.id = ls.lead_id 
				left join lead_opportunity as lo on lo.lead_id = ld.id 
				left join lead_opportunity_wise_po as po on po.lead_opportunity_id = lo.id	
				left join quotation as qt on qt.opportunity_id = lo.id	
				LEFT JOIN (
					SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
						if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
							FROM 
							(
								SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
								FROM `lead_stage_log` as t1 
								JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
								JOIN lead as ld on ld.id = t1.lead_id 
								JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
								JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
								WHERE ld.assigned_user_id IN (".$arg['filter_selected_user_id'].")  ORDER BY `lead_id`,t2.sort,t2.id
							) AS t
						) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null 
				where ld.assigned_user_id IN (".$arg['filter_selected_user_id'].") $datarange_ld GROUP BY ld.id
			) AS t1 GROUP BY t1.source_id ORDER by source_name";
		// echo $sql; die();		
		$query = $this->client_db->query($sql,false); 
		if($query){
			return $query->result_array();
		} else {
			return array();
		}
	}


	
	public function get_top_performers_of_month($arg)
	{
		$curr_ym=($arg['filter_selected_year_month'])?$arg['filter_selected_year_month']:date('Y-m');
		// $curr_y=date('Y');
		// $curr_m=date('m');
		$subsql='';
		// $subsql .= " AND t1.id IN (".$arg['filter_selected_user_id'].")";

		$sql="SELECT t1.id,t1.name,
		t1.photo,
		t2.name AS designation,
		SUM(IF(DATE_FORMAT(t5.po_date,'%Y-%m')='".$curr_ym."',t4.deal_value_as_per_purchase_order,0)) AS total_deal_value_as_per_purchase_order,
		SUM(IF(DATE_FORMAT(t5.po_date,'%Y-%m')='".$curr_ym."',1,0)) AS total_sales_count
		FROM user AS t1 
		INNER JOIN designation t2 ON t1.designation_id=t2.id 
		LEFT JOIN lead AS t3 ON t1.id=t3.assigned_user_id 
		LEFT JOIN lead_opportunity AS t4 ON t3.id=t4.lead_id 
		LEFT JOIN lead_opportunity_wise_po as t5 ON t4.id=t5.lead_opportunity_id	
		WHERE t5.is_cancel='N' $subsql  GROUP BY t1.id ORDER BY SUM(IF(DATE_FORMAT(t5.po_date,'%Y-%m')='".$curr_ym."',t4.deal_value_as_per_purchase_order,0)) DESC,COUNT(if(t4.status=4,1,NULL)) DESC";	
		
		
		
		// echo $sql; die();
		$query = $this->client_db->query($sql,false); 

		if($query){
			return $query->result_array();

		} else {
			return array();
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
		return $query->result();
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
		return $query->result();
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
		// return $query->result();
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
		// return $result->result();
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
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_daily_sales_report_detail($arg)
	{
		$filter_report=$arg['filter_report'];
		$start=$arg['start'];
        $limit=$arg['limit'];
		$limit_str="";
		if($limit!=''){
			$limit_str="LIMIT ".$start.",".$limit;
		}
		

		if($filter_report=='daily_sales_report')
		{
			$filter1=$arg['filter1'];
			$date=$arg['filter2'];
			$selected_user_id=$arg['filter_selected_user_id'];
			if($filter1=='calls')
			{
				$result = array();        
				$subsql = '';   
				$subsqlInner='';
				$group_by_str=" GROUP BY t1.id";
				$order_by_str = " ORDER BY t1.call_start DESC ";

				// ---------------------------------------
				// SEARCH VALUE 
				// if($argument['lead_id']!='')
				// {
				// 	$subsql.=" AND t1.tagged_lead_id ='".$argument['lead_id']."'";
				// } 
				if($selected_user_id!='')
				{
					$subsql.=" AND t1.user_id IN (".$selected_user_id.")";
				} 

				if($date!='')
				{
					$subsql .= " AND DATE(t1.call_start)='".$date."'"; 
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
					t1.tagged_lead_id,
					t1.status,
					t1.status_wise_msg,
					t1.created_at,
					t2.name AS assigned_user_name,
					c_paying_info.* 			 
					FROM tbl_call_history_for_lead_tmp AS t1 
					INNER JOIN user AS t2 ON t1.user_id=t2.id  
					$subsqlInner
					WHERE t1.is_deleted='N' AND t1.status!=1 $subsql $group_by_str $order_by_str $limit_str"; 
					
				$query = $this->client_db->query($sql,false);
				if($query){
					return $query->result();
				}
				else{
					return (object)array();
				}
			}
			else
			{
				if($filter1=='new_lead')
				{			
					$sql="SELECT ld.id AS lead_id,ld.title,ld.create_date,ld.deal_value,
							t1.name AS source_name,
							t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
							t3.name AS assigned_user,
							lo.deal_value AS dv,
							lo.deal_value_as_per_purchase_order,
							lls.lid,
							lls.last_stage_id,
							lls.name AS lead_ls
						FROM lead as ld	
						INNER JOIN source AS t1 ON t1.id=ld.source_id 
						INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
						INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
						LEFT JOIN(
							SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
						) AS lo_max ON lo_max.ldid = ld.id  
						LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 
						LEFT JOIN (
						SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
							if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
								FROM 
								(
									SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
									FROM `lead_stage_log` as t1 
									JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
									JOIN lead as ld on ld.id = t1.lead_id 
									JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
									JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
									WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
								) AS t
							) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null
						WHERE ld.assigned_user_id IN (".$selected_user_id.") AND ld.create_date='".$date."' $limit_str";
				}
				else if($filter1=='updated')
				{
					

					/*$sql="SELECT DATE(lc.lc_cd) AS create_date,ld.id AS lead_id,ld.title,ld.deal_value,
					t1.name AS source_name,t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,lls.lid,lls.last_stage_id,lls.name AS lead_ls				
							FROM  lead_comment as cm 
							JOIN (
								SELECT lead_id,count(lead_id) as total_comment,DATE(create_date) AS lc_cd
								FROM lead_comment 
								WHERE user_id IN (".$selected_user_id.") AND DATE(create_date)='".$date."' AND (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%')  
								GROUP BY lead_id HAVING total_comment>=1
								) as lc on cm.lead_id = lc.lead_id 
							INNER JOIN lead AS ld ON cm.lead_id=ld.id 
							INNER JOIN source AS t1 ON t1.id=ld.source_id 
							INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
							INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
							LEFT JOIN(
								SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
							) AS lo_max ON lo_max.ldid = ld.id  
							LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 
							 
							LEFT JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
								if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
								FROM 
								(
									SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
									FROM `lead_stage_log` as t1 
									JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
									JOIN lead as ld on ld.id = t1.lead_id 
									JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
									JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
									WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
								) AS t
								) AS lls ON cm.lead_id=lls.lid AND lls.last_stage_id is not null 
							WHERE cm.user_id IN (".$selected_user_id.") AND DATE(lc.lc_cd)='".$date."' GROUP BY lc.lead_id $limit_str"; */
					$sql="SELECT DATE(lc.lc_cd) AS create_date,ld.id AS lead_id,ld.title,ld.deal_value,
					t1.name AS source_name,t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,lls.lid,lls.last_stage_id,lls.name AS lead_ls				
							FROM  lead_comment as cm 
							JOIN (
								SELECT lead_id,count(lead_id) as total_comment,DATE(create_date) AS lc_cd
								FROM lead_comment 
								WHERE user_id IN (".$selected_user_id.") AND DATE(create_date)='".$date."' AND (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%')  
								GROUP BY lead_id HAVING total_comment>=1
								) as lc on cm.lead_id = lc.lead_id 
							INNER JOIN lead AS ld ON cm.lead_id=ld.id 
							INNER JOIN source AS t1 ON t1.id=ld.source_id 
							INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
							INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
							LEFT JOIN(
								SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
							) AS lo_max ON lo_max.ldid = ld.id  
							LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 
							 
							LEFT JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
								if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
								FROM 
								(
									SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
									FROM `lead_stage_log` as t1 
									JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
									JOIN lead as ld on ld.id = t1.lead_id 
									JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
									JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
									 ORDER BY `lead_id`,t2.sort,t2.id
								) AS t
								) AS lls ON cm.lead_id=lls.lid AND lls.last_stage_id is not null 
							WHERE cm.user_id IN (".$selected_user_id.") AND DATE(lc.lc_cd)='".$date."' GROUP BY lc.lead_id $limit_str";
				}
				else if($filter1=='quoted')
				{
					$sql="SELECT ls.lead_id,date(create_datetime) AS create_date,ld.id AS lead_id,ld.title,ld.deal_value,
					t1.name AS source_name,
					t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,lls.lid,lls.last_stage_id,lls.name AS lead_ls
						FROM lead_stage_log as ls
						JOIN lead as ld on ld.id = ls.lead_id 
						INNER JOIN source AS t1 ON t1.id=ld.source_id 
						INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
						INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
						LEFT JOIN(
							SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
						) AS lo_max ON lo_max.ldid = ld.id  
						LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 

						
						LEFT JOIN (
						SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
						if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
						FROM 
						(
							SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
							FROM `lead_stage_log` as t1 
							JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
							JOIN lead as ld on ld.id = t1.lead_id 
							JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
							JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
							WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
						) AS t
						) AS lls ON ls.lead_id=lls.lid AND lls.last_stage_id is not null
						WHERE ld.assigned_user_id IN (".$selected_user_id.") AND DATE(create_datetime)='".$date."' AND ls.stage_id ='2' GROUP BY ld.id $limit_str";
				}
				else if($filter1=='deal_won')
				{
					$sql="SELECT date(create_datetime) as create_date,ld.id AS lead_id,ld.title,ld.deal_value,t1.name AS source_name,
					t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,lls.lid,lls.last_stage_id,lls.name AS lead_ls
					FROM lead_stage_log as ls
					JOIN lead as ld on ld.id = ls.lead_id 
					INNER JOIN source AS t1 ON t1.id=ld.source_id 
					INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
					INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
					LEFT JOIN(
						SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity WHERE status='4' GROUP BY lead_id
					) AS lo_max ON lo_max.ldid = ld.id  
					LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id
					
					LEFT JOIN (
						SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
						if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
						FROM 
						(
							SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
							FROM `lead_stage_log` as t1 
							JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
							JOIN lead as ld on ld.id = t1.lead_id 
							JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
							JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
							WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
						) AS t
					) AS lls ON ls.lead_id=lls.lid AND lls.last_stage_id is not null
					WHERE ld.assigned_user_id IN (".$selected_user_id.") AND DATE(create_datetime)='".$date."'  AND ls.stage_id ='4' GROUP BY ld.id $limit_str";
				}
				else if($filter1=='deal_lost')
				{
					
					$sql="SELECT 
						t0.ls_cd AS create_date,
						ld.id AS lead_id,lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,ld.title,t1.name AS source_name,
						t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
						t3.name AS assigned_user,lls.lid,lls.last_stage_id,lls.name AS lead_ls				
						FROM `lead` as ld
						JOIN (
							SELECT ls.lead_id,
							max(if(ls.stage_id = 3 || ls.stage_id = 5||ls.stage_id = 7 ,1,0)) as is_lost,
							date(ls.create_datetime) AS ls_cd
							FROM `lead` as ld 
							left JOIN lead_stage_log as ls on ld.id = ls.lead_id 					
							WHERE ld.assigned_user_id IN (".$selected_user_id.") AND ls.stage_id IN (3,5,7) AND date(ls.create_datetime)='".$date."'
							GROUP BY ld.id  HAVING is_lost=1 
						) AS t0 on ld.id = t0.lead_id 
						INNER JOIN source AS t1 ON t1.id=ld.source_id 
						INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
						INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 						
						LEFT JOIN(
							SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
						) AS lo_max ON lo_max.ldid = ld.id  
						LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id
						LEFT JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
							if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
							FROM 
							(
								SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
								FROM `lead_stage_log` as t1 
								JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
								JOIN lead as ld on ld.id = t1.lead_id 
								JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
								JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
								WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
							) AS t
						) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null GROUP BY ld.id $limit_str";
				}
				else if($filter1=='revenue')
				{
					$sql="SELECT po.create_date,ld.id AS lead_id,lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,ld.title,t1.name AS source_name,
						t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
						t3.name AS assigned_user,lls.lid,lls.last_stage_id,lls.name AS lead_ls
						FROM lead_opportunity AS lo
						JOIN `lead` AS ld ON ld.id = lo.lead_id
						JOIN lead_opportunity_wise_po AS po ON lo.id = po.lead_opportunity_id 
						INNER JOIN source AS t1 ON t1.id=ld.source_id 
						INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
						INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
						LEFT JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
							if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
							FROM 
							(
								SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
								FROM `lead_stage_log` as t1 
								JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
								JOIN lead as ld on ld.id = t1.lead_id 
								JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
								JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
								WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
							) AS t
						) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null
						WHERE ld.assigned_user_id IN (".$selected_user_id.")  AND po.create_date='".$date."' $limit_str";
				}

				$query = $this->client_db->query($sql,false); 
				if($query){
					return $query->result_array();	
				} else {
					return array();
				}
			}
		}
		else if($filter_report=='lead_by_source_report')
		{
			$filter1=$arg['filter1'];
			$source_id=$arg['filter2'];
			$selected_user_id=$arg['filter_selected_user_id'];

			if($arg['filter_date_range_pre_define']!='')
			{			
				$current_date=date("Y-m-d");
				if($arg['filter_date_range_pre_define']=='TODAY')
				{
					$last_day=0;
				}
				if($arg['filter_date_range_pre_define']=='YESTERDAY')
				{
					$last_day=1;
				}
				if($arg['filter_date_range_pre_define']=='LAST7DAYS')
				{
					$last_day=7;
				}
				if($arg['filter_date_range_pre_define']=='LAST15DAYS')
				{
					$last_day=15;
				}
				if($arg['filter_date_range_pre_define']=='LAST30DAYS')
				{
					$last_day=30;
				}
				
				if($arg['filter_date_range_pre_define']!='TILLDATE')
				{				
					$datarange_ld=" AND ld.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				}
				
			}
			else
			{
				if($arg['filter_date_range_user_define_from']!='' && $arg['filter_date_range_user_define_to']!='')
				{
					$start_date=date_display_format_to_db_format($arg['filter_date_range_user_define_from']);
					$end_date=date_display_format_to_db_format($arg['filter_date_range_user_define_to']);
					$datarange_ld=" AND (ld.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
					
				}
			}

			if($filter1=='new_lead')
			{			
				$sql="SELECT sr.name AS source_name,
						ld.id AS lead_id,ld.title,ld.create_date,ld.deal_value,
						lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,
						t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
						t3.name AS assigned_user,
						lls.lid,lls.last_stage_id,lls.name AS lead_ls,
						currency.code AS lo_currency_code
						FROM `source` as sr 
						join lead as ld on sr.id = ld.source_id 
						join lead_stage_log as ls on ld.id = ls.lead_id 
						left join lead_opportunity as lo on lo.lead_id = ld.id 
						left join lead_opportunity_wise_po as po on po.lead_opportunity_id = lo.id	
						left join quotation as qt on qt.opportunity_id = lo.id	
						INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
						INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
						LEFT JOIN currency ON currency.id=lo.currency_type
						LEFT JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
								if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
									FROM 
									(
										SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
										FROM `lead_stage_log` as t1 
										JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
										JOIN lead as ld on ld.id = t1.lead_id 
										JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
										JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
										WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
									) AS t
								) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null
						where ld.assigned_user_id IN (".$selected_user_id.") AND ld.source_id='".$source_id."' $datarange_ld  GROUP BY ld.id $limit_str";

				// $sql="SELECT ld.id AS lead_id,ld.title,ld.create_date,ld.deal_value,
				// 		t1.name AS source_name,
				// 		t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
				// 		t3.name AS assigned_user,
				// 		lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,lls.lid,lls.last_stage_id,lls.name AS lead_ls
				// 	FROM lead as ld	
				// 	INNER JOIN source AS t1 ON t1.id=ld.source_id 
				// 	INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
				// 	INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
				// 	LEFT JOIN(
				// 		SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
				// 	) AS lo_max ON lo_max.ldid = ld.id  
				// 	LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 
				// 	LEFT JOIN (
				// 	SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
				// 		if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
				// 							FROM 
				// 							(
				// 								SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
				// 								FROM `lead_stage_log` as t1 
				// 								JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
				// 								JOIN lead as ld on ld.id = t1.lead_id 
				// 								JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
				// 								JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
				// 								WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
				// 							) AS t
				// 		) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null
				// 	WHERE ld.assigned_user_id IN (".$selected_user_id.") AND ld.create_date='".$date."' $limit_str";
			}
			else if($filter1=='active_lead')
			{
				$sql="SELECT sr.name AS source_name,
						ld.id AS lead_id,ld.title,ld.create_date,ld.deal_value,
						lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,
						t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
						t3.name AS assigned_user,
						lls.lid,lls.last_stage_id,lls.name AS lead_ls,
						currency.code AS lo_currency_code
						FROM `source` as sr 
						join lead as ld on sr.id = ld.source_id 
						join lead_stage_log as ls on ld.id = ls.lead_id 
						left join lead_opportunity as lo on lo.lead_id = ld.id 
						left join lead_opportunity_wise_po as po on po.lead_opportunity_id = lo.id	
						left join quotation as qt on qt.opportunity_id = lo.id	
						INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
						INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
						LEFT JOIN currency ON currency.id=lo.currency_type
						INNER JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
								if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
									FROM 
									(
										SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
										FROM `lead_stage_log` as t1 
										JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
										JOIN lead as ld on ld.id = t1.lead_id 
										JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
										JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
										WHERE ld.assigned_user_id IN (".$selected_user_id.")  ORDER BY `lead_id`,t2.sort,t2.id
									) AS t
								) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null 
						where ld.assigned_user_id IN (".$selected_user_id.") AND ld.source_id='".$source_id."' AND lls.last_stage_id NOT IN (3,4,5,6,7) $datarange_ld  GROUP BY ld.id $limit_str";
					// echo $sql; die();
			}
			else if($filter1=='quoted')
			{
				$sql="SELECT sr.name AS source_name,
						ld.id AS lead_id,ld.title,ld.create_date,ld.deal_value,
						lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,
						t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
						t3.name AS assigned_user,
						lls.lid,lls.last_stage_id,lls.name AS lead_ls,
						currency.code AS lo_currency_code
						FROM `source` as sr 
						join lead as ld on sr.id = ld.source_id 
						join lead_stage_log as ls on ld.id = ls.lead_id 
						left join lead_opportunity as lo on lo.lead_id = ld.id 
						left join lead_opportunity_wise_po as po on po.lead_opportunity_id = lo.id	
						left join quotation as qt on qt.opportunity_id = lo.id	
						INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
						INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
						LEFT JOIN currency ON currency.id=lo.currency_type
						INNER JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
								if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
									FROM 
									(
										SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
										FROM `lead_stage_log` as t1 
										JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
										JOIN lead as ld on ld.id = t1.lead_id 
										JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
										JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
										WHERE ld.assigned_user_id IN (".$selected_user_id.")  ORDER BY `lead_id`,t2.sort,t2.id
									) AS t
								) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null 
						where ld.assigned_user_id IN (".$selected_user_id.") AND ld.source_id='".$source_id."' AND lls.last_stage_id='2' $datarange_ld  GROUP BY ld.id $limit_str";
						
			}
			else if($filter1=='deal_won')
			{
				$sql="SELECT sr.name AS source_name,
						ld.id AS lead_id,ld.title,ld.create_date,ld.deal_value,
						lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,
						t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
						t3.name AS assigned_user,
						lls.lid,lls.last_stage_id,lls.name AS lead_ls,
						currency.code AS lo_currency_code
						FROM `source` as sr 
						join lead as ld on sr.id = ld.source_id 
						join lead_stage_log as ls on ld.id = ls.lead_id 
						left join lead_opportunity as lo on lo.lead_id = ld.id 
						left join lead_opportunity_wise_po as po on po.lead_opportunity_id = lo.id	
						left join quotation as qt on qt.opportunity_id = lo.id	
						LEFT JOIN currency ON currency.id=lo.currency_type
						INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
						INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
						INNER JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
								if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
									FROM 
									(
										SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
										FROM `lead_stage_log` as t1 
										JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
										JOIN lead as ld on ld.id = t1.lead_id 
										JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
										JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
										WHERE ld.assigned_user_id IN (".$selected_user_id.")  ORDER BY `lead_id`,t2.sort,t2.id
									) AS t
								) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null 
						where ld.assigned_user_id IN (".$selected_user_id.") AND ld.source_id='".$source_id."' AND lls.last_stage_id='4' $datarange_ld  GROUP BY ld.id $limit_str";
				// echo $sql;die();
			}
			else if($filter1=='deal_lost')
			{
				
				$sql="SELECT sr.name AS source_name,
						ld.id AS lead_id,ld.title,ld.create_date,ld.deal_value,
						lo.deal_value AS dv,lo.deal_value_as_per_purchase_order,
						t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
						t3.name AS assigned_user,
						lls.lid,lls.last_stage_id,lls.name AS lead_ls,
						currency.code AS lo_currency_code
						FROM `source` as sr 
						join lead as ld on sr.id = ld.source_id 
						join lead_stage_log as ls on ld.id = ls.lead_id 
						left join lead_opportunity as lo on lo.lead_id = ld.id 
						left join lead_opportunity_wise_po as po on po.lead_opportunity_id = lo.id	
						left join quotation as qt on qt.opportunity_id = lo.id	
						LEFT JOIN currency ON currency.id=lo.currency_type
						INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
						INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
						INNER JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
								if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
									FROM 
									(
										SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
										FROM `lead_stage_log` as t1 
										JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
										JOIN lead as ld on ld.id = t1.lead_id 
										JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
										JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
										WHERE ld.assigned_user_id IN (".$selected_user_id.")  ORDER BY `lead_id`,t2.sort,t2.id
									) AS t
								) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null 
						where ld.assigned_user_id IN (".$selected_user_id.") AND ld.source_id='".$source_id."' AND lls.last_stage_id='5' $datarange_ld  GROUP BY ld.id $limit_str";
			}
			

			$query = $this->client_db->query($sql,false); 
			if($query){
				return $query->result_array();	
			} else {
				return array();
			}
		}
		else if($filter_report=='user_activity_report')
		{
			$filter1=$arg['filter1'];
			$user_id=$arg['filter2'];
			$selected_user_id=$arg['filter_selected_user_id'];

			if($arg['filter_date_range_pre_define']!='')
			{			
				$current_date=date("Y-m-d");
				if($arg['filter_date_range_pre_define']=='TODAY')
				{
					$last_day=0;
				}
				if($arg['filter_date_range_pre_define']=='YESTERDAY')
				{
					$last_day=1;
				}
				if($arg['filter_date_range_pre_define']=='LAST7DAYS')
				{
					$last_day=7;
				}
				if($arg['filter_date_range_pre_define']=='LAST15DAYS')
				{
					$last_day=15;
				}
				if($arg['filter_date_range_pre_define']=='LAST30DAYS')
				{
					$last_day=30;
				}
				
				if($arg['filter_date_range_pre_define']!='TILLDATE')
				{				
					$datarange_ld=" AND ld.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
					$datarange_lc=" AND DATE(t1.call_start) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
					$datarange_m=" AND date(m.checkin_datetime) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
					$datarange_cm=" AND cm.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
					$datarange_lo=" AND lo.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
					$datarange_ls=" AND date(ls.create_datetime) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
					$datarange_po=" AND po.create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
					$datarange_common=" AND create_date >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
					$datarange_common_datetime=" AND DATE(create_datetime) >= (CURRENT_DATE -INTERVAL ".$last_day." day)";
				}
				
			}
			else
			{
				if($arg['filter_date_range_user_define_from']!='' && $arg['filter_date_range_user_define_to']!='')
				{
					$start_date=date_display_format_to_db_format($arg['filter_date_range_user_define_from']);
					$end_date=date_display_format_to_db_format($arg['filter_date_range_user_define_to']);
					$datarange_ld=" AND (ld.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
					$datarange_lc=" AND (DATE(t1.call_start) BETWEEN '".$start_date."' AND '".$end_date."')";
					$datarange_m=" AND (date(m.checkin_datetime) BETWEEN '".$start_date."' AND '".$end_date."')";
					$datarange_cm=" AND (cm.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
					$datarange_lo=" AND (lo.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
					$datarange_ls=" AND (date(ls.create_datetime) BETWEEN '".$start_date."' AND '".$end_date."')";
					$datarange_po=" AND (po.create_date BETWEEN '".$start_date."' AND '".$end_date."')";
					$datarange_common=" AND (DATE(create_date) BETWEEN '".$start_date."' AND '".$end_date."')";
					$datarange_common_datetime=" AND (DATE(create_datetime) BETWEEN '".$start_date."' AND '".$end_date."')";
					
				}
			}
			if($filter1=='calls')
			{
				$result = array();        
				$subsql = '';   
				$subsqlInner='';
				$group_by_str=" GROUP BY t1.id";
				$order_by_str = " ORDER BY t1.call_start DESC ";

				// ---------------------------------------
				// SEARCH VALUE 
				// if($argument['lead_id']!='')
				// {
				// 	$subsql.=" AND t1.tagged_lead_id ='".$argument['lead_id']."'";
				// } 
				if($user_id!='')
				{
					$subsql.=" AND t1.user_id='".$user_id."'";
				} 

				if($datarange_lc!='')
				{
					$subsql .= $datarange_lc; 
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
					t1.tagged_lead_id,
					t1.status,
					t1.status_wise_msg,
					t1.created_at,
					t2.name AS assigned_user_name,
					c_paying_info.* 			 
					FROM tbl_call_history_for_lead_tmp AS t1 
					INNER JOIN user AS t2 ON t1.user_id=t2.id  
					$subsqlInner
					WHERE t1.is_deleted='N' AND t1.status!=1 $subsql $group_by_str $order_by_str $limit_str"; 
				// echo $sql; die();
				$query = $this->client_db->query($sql,false);
				if($query){
					return $query->result();
				}
				else{
					return (object)array();
				}
			}
			else
			{
				
				if($filter1=='new_lead')
				{			
					
					$sql="SELECT ld.assigned_user_id,
						ld.id AS lead_id,ld.title,ld.create_date,
						ld.deal_value,
						ld.deal_value_currency_code,
						t1.name AS source_name,
						t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
						t3.name AS assigned_user,
						lo.deal_value AS dv,
						lo.deal_value_as_per_purchase_order,
						currency.code AS lo_currency_code,
						lls.lid,lls.last_stage_id,lls.name AS lead_ls
						FROM lead AS ld 
						INNER JOIN source AS t1 ON t1.id=ld.source_id 
						INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
						INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id
						LEFT JOIN(
							SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
						) AS lo_max ON lo_max.ldid = ld.id  
						LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 
						LEFT JOIN currency ON currency.id=lo.currency_type						
						LEFT JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
							if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
							FROM 
							(
								SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
								FROM `lead_stage_log` as t1 
								JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
								JOIN lead as ld on ld.id = t1.lead_id 
								JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
								JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
								WHERE ld.assigned_user_id = '".$user_id."' ORDER BY `lead_id`,t2.sort,t2.id
							) AS t
						) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null 
						WHERE ld.assigned_user_id = '".$user_id."' $datarange_ld GROUP BY ld.id $limit_str";
				}
				else if($filter1=='updated')
				{
					/* $sql="SELECT cm.user_id as assigned_user_id,ld.id AS lead_id,ld.title,
					ld.deal_value,
					ld.create_date,
					ld.deal_value_currency_code,
					t1.name AS source_name,t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,
					lo.deal_value AS dv,
					lo.deal_value_as_per_purchase_order,
					currency.code AS lo_currency_code,
					lls.lid,lls.last_stage_id,lls.name AS lead_ls				
					from  lead_comment as cm 
					join (
						select lead_id,count(lead_id) as total_comment
						from lead_comment 
						where user_id = '".$user_id."' AND (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%') $datarange_common 
						group by lead_id  
						HAVING total_comment>=1
						) as lc on cm.lead_id = lc.lead_id 
					INNER JOIN lead AS ld ON cm.lead_id=ld.id 
					INNER JOIN source AS t1 ON t1.id=ld.source_id 
					INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
					INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
					LEFT JOIN(
						SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
					) AS lo_max ON lo_max.ldid = ld.id  
					LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 
					LEFT JOIN currency ON currency.id=lo.currency_type
					LEFT JOIN (
					SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
						if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
						FROM 
						(
							SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
							FROM `lead_stage_log` as t1 
							JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
							JOIN lead as ld on ld.id = t1.lead_id 
							JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
							JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
							WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
						) AS t
					) AS lls ON cm.lead_id=lls.lid AND lls.last_stage_id is not null
					WHERE cm.user_id = '".$user_id."' $datarange_cm GROUP BY cm.lead_id $limit_str"; */
					$sql="SELECT cm.user_id as assigned_user_id,ld.id AS lead_id,ld.title,
					ld.deal_value,
					ld.create_date,
					ld.deal_value_currency_code,
					t1.name AS source_name,t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,
					lo.deal_value AS dv,
					lo.deal_value_as_per_purchase_order,
					currency.code AS lo_currency_code,
					lls.lid,lls.last_stage_id,lls.name AS lead_ls				
					from  lead_comment as cm 
					join (
						select lead_id,count(lead_id) as total_comment
						from lead_comment 
						where user_id = '".$user_id."' AND (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%') $datarange_common 
						group by lead_id  
						HAVING total_comment>=1
						) as lc on cm.lead_id = lc.lead_id 
					INNER JOIN lead AS ld ON cm.lead_id=ld.id 
					INNER JOIN source AS t1 ON t1.id=ld.source_id 
					INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
					INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
					LEFT JOIN(
						SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
					) AS lo_max ON lo_max.ldid = ld.id  
					LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 
					LEFT JOIN currency ON currency.id=lo.currency_type
					LEFT JOIN (
					SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
						if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
						FROM 
						(
							SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
							FROM `lead_stage_log` as t1 
							JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
							JOIN lead as ld on ld.id = t1.lead_id 
							JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
							JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
							 ORDER BY `lead_id`,t2.sort,t2.id
						) AS t
					) AS lls ON cm.lead_id=lls.lid AND lls.last_stage_id is not null
					WHERE cm.user_id = '".$user_id."' $datarange_cm GROUP BY cm.lead_id $limit_str";
					
					
				}
				else if($filter1=='quoted')
				{
					$sql="SELECT ld.assigned_user_id,ld.id AS lead_id,ld.title,
					ld.deal_value,
					ld.create_date,
					ld.deal_value_currency_code,
					t1.name AS source_name,
					t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,
					lo.deal_value AS dv,
					lo.deal_value_as_per_purchase_order,
					currency.code AS lo_currency_code,
					lls.lid,lls.last_stage_id,lls.name AS lead_ls	
					FROM lead_stage_log as ls 
					JOIN lead AS ld ON ld.id = ls.lead_id 
					INNER JOIN source AS t1 ON t1.id=ld.source_id 
					INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
					INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
					LEFT JOIN(
						SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
					) AS lo_max ON lo_max.ldid = ld.id  
					LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 
					LEFT JOIN currency ON currency.id=lo.currency_type
					LEFT JOIN (
					SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
					if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
					FROM 
					(
						SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
						FROM `lead_stage_log` as t1 
						JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
						JOIN lead as ld on ld.id = t1.lead_id 
						JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
						JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
						WHERE ld.assigned_user_id = '".$user_id."' ORDER BY `lead_id`,t2.sort,t2.id
					) AS t
					) AS lls ON ls.lead_id=lls.lid AND lls.last_stage_id is not null
					WHERE ld.assigned_user_id = '".$user_id."' AND ls.stage_id =2 $datarange_common_datetime GROUP by ls.lead_id $limit_str";					
				}
				else if($filter1=='deal_won')
				{
					$sql="SELECT ld.assigned_user_id,ld.id AS lead_id,ld.title,ld.deal_value,ld.create_date,t1.name AS source_name,
					t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,
					lo.deal_value AS dv,
					lo.deal_value_as_per_purchase_order,
					currency.code AS lo_currency_code,
					lls.lid,lls.last_stage_id,lls.name AS lead_ls  
					FROM lead_stage_log as ls JOIN lead as ld on ld.id = ls.lead_id	
					INNER JOIN source AS t1 ON t1.id=ld.source_id 
					INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
					INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
					LEFT JOIN(
						SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity WHERE status='4' GROUP BY lead_id
					) AS lo_max ON lo_max.ldid = ld.id  
					LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id		
					LEFT JOIN currency ON currency.id=lo.currency_type			
					LEFT JOIN (
						SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
						if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
						FROM 
						(
							SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
							FROM `lead_stage_log` as t1 
							JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
							JOIN lead as ld on ld.id = t1.lead_id 
							JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
							JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
							WHERE ld.assigned_user_id = '".$user_id."' ORDER BY `lead_id`,t2.sort,t2.id
						) AS t
					) AS lls ON ls.lead_id=lls.lid AND lls.last_stage_id is not null 
					WHERE ld.assigned_user_id = '".$user_id."' AND ls.stage_id =4 $datarange_common_datetime GROUP by ls.lead_id $limit_str";					
				}
				else if($filter1=='deal_lost')
				{
					$sql="SELECT 
					ld.assigned_user_id,ld.id AS lead_id,ld.create_date,
					lo.deal_value AS dv,
					lo.deal_value_as_per_purchase_order,
					currency.code AS lo_currency_code,
					ld.title,t1.name AS source_name,
					t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,lls.lid,lls.last_stage_id,lls.name AS lead_ls	
					FROM `lead` as ld
					JOIN (
						SELECT ls.lead_id,
						max(if(ls.stage_id = 3 || ls.stage_id = 5||ls.stage_id = 7 ,1,0)) as is_lost,
						date(ls.create_datetime) AS ls_cd
						FROM `lead` as ld 
						left JOIN lead_stage_log as ls on ld.id = ls.lead_id 					
						WHERE ld.assigned_user_id = '".$user_id."' AND ls.stage_id IN (3,5,7) $datarange_ls
						GROUP BY ld.id  HAVING is_lost=1 
					) AS t0 on ld.id = t0.lead_id 

					INNER JOIN source AS t1 ON t1.id=ld.source_id 
					INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
					INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
					LEFT JOIN(
						SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
					) AS lo_max ON lo_max.ldid = ld.id  
					LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id			
					LEFT JOIN currency ON currency.id=lo.currency_type
					LEFT JOIN (
						SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
						if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
						FROM 
						(
							SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
							FROM `lead_stage_log` as t1 
							JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
							JOIN lead as ld on ld.id = t1.lead_id 
							JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
							JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
							WHERE ld.assigned_user_id = '".$user_id."' ORDER BY `lead_id`,t2.sort,t2.id
						) AS t
					) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null GROUP by ld.id $limit_str";
									
				}
				else if($filter1=='revenue')
				{
					$sql="SELECT ld.assigned_user_id,ld.id AS lead_id,ld.create_date,
					lo.deal_value AS dv,
					lo.deal_value_as_per_purchase_order,
					ld.title,t1.name AS source_name,
					t2.company_name AS cust_company_name,t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,lls.lid,lls.last_stage_id,lls.name AS lead_ls,
					currency.code AS lo_currency_code
					FROM lead_opportunity as lo
					JOIN `lead` as ld on ld.id = lo.lead_id
					JOIN lead_opportunity_wise_po AS po ON lo.id = po.lead_opportunity_id 
					INNER JOIN source AS t1 ON t1.id=ld.source_id 
					INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
					INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id 
					LEFT JOIN currency ON currency.id=lo.currency_type
					LEFT JOIN (
						SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
						if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
						FROM 
						(
							SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
							FROM `lead_stage_log` as t1 
							JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
							JOIN lead as ld on ld.id = t1.lead_id 
							JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
							JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
							WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
						) AS t
					) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null
					WHERE ld.assigned_user_id = '".$user_id."' $datarange_po GROUP by ld.id $limit_str";					
				}
				// echo $sql; die();
				$query = $this->client_db->query($sql,false); 
				if($query){
					return $query->result_array();	
				} else {
					return array();
				}
			}
		}	
		else if($filter_report=='user_wise_sales_pipeline_report')
		{
			$stage_id=$arg['filter1'];
			$user_id=$arg['filter2'];
			$sql="SELECT t1.last_stage_id,
			t1.assigned_user_id,
			t1.lead_id,
			t1.deal_value,
			t1.deal_value_currency_code,
			lo.deal_value AS dv,
			lo.deal_value_as_per_purchase_order,
			currency.code AS lo_currency_code,
			t1.title,
			t5.name AS source_name,
			t3.company_name AS cust_company_name,t3.contact_person AS cust_contact_person,
			t4.name AS assigned_user,t1.lead_ls,t1.create_date
			FROM 
			(
				select t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
				from 
				(
					SELECT t1.id,t1.lead_id,t1.stage_id,t2.name AS lead_ls,t2.sort,t3.total_stage,
					ld.assigned_user_id,ld.title,ld.source_id,ld.customer_id,ld.create_date,ld.deal_value,ld.deal_value_currency_code
					FROM `lead_stage_log` as t1 
					join (SELECT * FROM `opportunity_stage` WHERE id not in (3,4,5,6,7) and `is_active_lead`='Y' and `is_deleted` = 'N' ORDER BY sort) as t2 
					on t1.stage_id = t2.id
					join lead as ld on ld.id = t1.lead_id 
					join (select @sl:=0,@ld:=0) as v on 1=1
					join (select lead_id,count(stage_id) as total_stage,max(if(stage_id in (3,4,5,6,7),1,0)) inactive from lead_stage_log   group by lead_id  having inactive=0) as t3 
						on t1.lead_id = t3.lead_id	where ld.assigned_user_id ='".$user_id."' order by `lead_id`,t2.sort
				) as t
			) AS t1 			
			LEFT JOIN(
				SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
			) AS lo_max ON lo_max.ldid = t1.lead_id  
			LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 
			INNER JOIN source AS t2 ON t2.id=t1.source_id 
			INNER JOIN customer AS t3 ON t1.customer_id=t3.id 
			INNER JOIN user AS t4 ON t1.assigned_user_id=t4.id 
			INNER JOIN source AS t5 ON t5.id=t1.source_id 
			LEFT JOIN currency ON currency.id=lo.currency_type
			WHERE last_stage_id ='".$stage_id."' GROUP BY t1.lead_id $limit_str";
				
			$query = $this->client_db->query($sql,false); 
			if($query){
				return $query->result_array();	
			} else {
				return array();
			}
		}
		else if($filter_report=='lead_pipeline_report')
		{
			$selected_user_id=$arg['filter_selected_user_id'];
			$stage_id=$arg['filter1'];			
			$sql="SELECT t1.*,t2.name AS source_name,t3.company_name AS cust_company_name,
						t3.contact_person AS cust_contact_person,t4.name AS assigned_user,
						lead_opportunity.deal_value AS dv,lead_opportunity.deal_value_as_per_purchase_order,currency.code AS lo_currency_code
					FROM 
					(
						SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
						FROM 
						(
							SELECT t1.id,
									t1.lead_id,
									t1.stage_id,
									t2.name AS lead_ls,
									t2.sort,t3.total_stage,
									ld.assigned_user_id,
									ld.id AS ld_id,
									ld.title,
									ld.create_date,
									ld.deal_value,
									ld.deal_value_currency_code,
									ld.source_id,
									ld.customer_id
							FROM `lead_stage_log` as t1 
							JOIN (SELECT * FROM `opportunity_stage` WHERE id NOT IN (3,4,5,6,7) AND `is_active_lead`='Y' AND `is_deleted` = 'N' ORDER BY sort) AS t2 ON t1.stage_id = t2.id
							JOIN lead as ld on ld.id = t1.lead_id 
							JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
							JOIN (SELECT lead_id,count(stage_id) AS total_stage,max(if(stage_id in (3,4,5,6,7),1,0)) inactive from lead_stage_log  group by lead_id having inactive=0) as t3 ON t1.lead_id = t3.lead_id
							WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
						) AS t
					) AS t1	
					INNER JOIN source AS t2 ON t2.id=t1.source_id	
					INNER JOIN customer AS t3 ON t1.customer_id=t3.id 	
					INNER JOIN user AS t4 ON t1.assigned_user_id=t4.id	
					LEFT JOIN lead_opportunity ON lead_opportunity.lead_id=t1.lead_id
					LEFT JOIN currency ON currency.id=lead_opportunity.currency_type
					WHERE last_stage_id='".$stage_id."' and last_stage_id IS NOT null GROUP BY t1.lead_id $limit_str";	
			// echo $sql; die();			
			$query = $this->client_db->query($sql,false); 
			if($query){
				return $query->result_array();	
			} else {
				return array();
			}
		}
		else if($filter_report=='top_performer_of_month')
		{
			$selected_user_id=$arg['filter_selected_user_id'];
			$ym=$arg['filter1'];	
			$user_id=$arg['filter2'];			
			$sql="SELECT t1.name AS assigned_user,
			t3.assigned_user_id,
			t3.id AS lead_id,t3.title,t3.create_date,t3.deal_value,source.name AS source_name,
			customer.company_name AS cust_company_name,customer.contact_person AS cust_contact_person,
			t4.deal_value AS dv,t4.deal_value_as_per_purchase_order,lls.lid,lls.last_stage_id,lls.name AS lead_ls
			
			FROM user AS t1 
			INNER JOIN designation t2 ON t1.designation_id=t2.id 
			LEFT JOIN lead AS t3 ON t1.id=t3.assigned_user_id 
			LEFT JOIN lead_opportunity AS t4 ON t3.id=t4.lead_id 
			LEFT JOIN lead_opportunity_wise_po as t5 ON t4.id=t5.lead_opportunity_id 
			INNER JOIN source ON source.id=t3.source_id 	
			INNER JOIN customer ON t3.customer_id=customer.id 
			LEFT JOIN (
				SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
				if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
				FROM 
				(
					SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
					FROM `lead_stage_log` as t1 
					JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
					JOIN lead as ld on ld.id = t1.lead_id 
					JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
					JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
					WHERE ld.assigned_user_id = '".$user_id."' ORDER BY `lead_id`,t2.sort,t2.id
				) AS t
			) AS lls ON t3.id=lls.lid AND lls.last_stage_id is not null 
			WHERE t1.id='".$user_id."' AND DATE_FORMAT(t5.po_date,'%Y-%m')='".$ym."' $limit_str";	
			// echo $sql; die();			
			$query = $this->client_db->query($sql,false); 
			if($query){
				return $query->result_array();	
			} else {
				return array();
			}
		}	
		else if($filter_report=='this_month_report')
		{
			$filter1=$arg['filter1'];
			$filter2=$arg['filter2'];
			$selected_user_id=$arg['filter_selected_user_id'];
			$filter_date_range_pre_define=$arg['filter_date_range_pre_define'];			
			if($filter1=='new_lead')
			{
				$sql="SELECT 
					ld.assigned_user_id,
					ld.id AS lead_id,ld.title,ld.create_date,
					ld.deal_value,
					ld.deal_value_currency_code,
					t1.name AS source_name,
					t2.company_name AS cust_company_name,
					t2.contact_person AS cust_contact_person,
					t3.name AS assigned_user,
					lo.deal_value AS dv,
					lo.deal_value_as_per_purchase_order,
					currency.code AS lo_currency_code,
					lls.lid,lls.last_stage_id,lls.name AS lead_ls
					FROM lead as ld 
					INNER JOIN source AS t1 ON t1.id=ld.source_id 
					INNER JOIN customer AS t2 ON ld.customer_id=t2.id 
					INNER JOIN user AS t3 ON ld.assigned_user_id=t3.id
					LEFT JOIN(
						SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
					) AS lo_max ON lo_max.ldid = ld.id  
					LEFT JOIN lead_opportunity AS lo ON lo.id = lo_max.latest_op_id 
					LEFT JOIN currency ON currency.id=lo.currency_type						
					LEFT JOIN (
						SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
						if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
						FROM 
						(
							SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
							FROM `lead_stage_log` as t1 
							JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
							JOIN lead as ld on ld.id = t1.lead_id 
							JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
							JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
							WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
						) AS t
					) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null  
					WHERE ld.assigned_user_id IN (".$selected_user_id.") AND DATE_FORMAT(ld.create_date,'%Y-%m')='".$filter_date_range_pre_define."' GROUP BY ld.id $limit_str";
			}
			else if($filter1=='sales_order')
			{
				$sql="SELECT 
						ld.assigned_user_id,
						ld.id AS lead_id,ld.title,ld.create_date,
						ld.deal_value,
						ld.deal_value_currency_code,
						source.name AS source_name,
						customer.company_name AS cust_company_name,
						customer.contact_person AS cust_contact_person,
						user.name AS assigned_user,
						lo.deal_value AS dv,
						lo.deal_value_as_per_purchase_order,
						currency.code AS lo_currency_code,
						lls.lid,lls.last_stage_id,lls.name AS lead_ls
						FROM lead_opportunity_wise_po as t1 
						JOIN lead_opportunity as lo on lo.id = t1.lead_opportunity_id
						JOIN lead as ld on ld.id = lo.lead_id 
						INNER JOIN source ON source.id=ld.source_id 
						INNER JOIN customer ON ld.customer_id=customer.id 
						INNER JOIN user ON ld.assigned_user_id=user.id
						LEFT JOIN(
							SELECT max(id) AS latest_op_id,lead_id AS ldid FROM lead_opportunity GROUP BY lead_id
						) AS lo_max ON lo_max.ldid = ld.id  						
						LEFT JOIN currency ON currency.id=lo.currency_type						
						LEFT JOIN (
							SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,
							if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
							FROM 
							(
								SELECT t1.id,t1.lead_id,t1.lead_id AS lid,t1.stage_id,t2.name,t2.sort,t3.total_stage
								FROM `lead_stage_log` as t1 
								JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) AS t2 ON t1.stage_id = t2.id
								JOIN lead as ld on ld.id = t1.lead_id 
								JOIN (SELECT @sl:=0,@ld:=0,@won:=0) AS v on 1=1
								JOIN (SELECT lead_id,count(stage_id) AS total_stage from lead_stage_log  group by lead_id) as t3 ON t1.lead_id = t3.lead_id
								WHERE ld.assigned_user_id IN (".$selected_user_id.") ORDER BY `lead_id`,t2.sort,t2.id
							) AS t
						) AS lls ON ld.id=lls.lid AND lls.last_stage_id is not null  
						WHERE t1.is_cancel='N' and ld.assigned_user_id IN (".$selected_user_id.") AND DATE_FORMAT(t1.po_date,'%Y-%m')='".$filter_date_range_pre_define."'  GROUP BY ld.id $limit_str";
			}
			// echo $sql; die();
			$query = $this->client_db->query($sql,false); 
			if($query){
				return $query->result_array();	
			} else {
				return array();
			}
			
		}		
	}

	public function get_stage_id_from_name($stage_name)
	{
		$sql="SELECT id FROM opportunity_stage WHERE name='".$stage_name."' AND is_deleted='N'";
		$query = $this->client_db->query($sql,false); 		
		if($query){
			return $query->row()->id;
		} 
		else{
			return 0;
		}
	}

	function get_user_tree_list($user_id=0,$level=0,$user_type='user',$service_order_detail_ids_str='')
    {
       	
        $result = array();  
        $subsql = '';
        if($level==0)
        {
			if($user_id){
				$subsql .= " AND t1.id='".$user_id."'";
			}
        	
        }
        else
        {
        	$subsql .= " AND t1.manager_id='".$user_id."'";
        }
		if($service_order_detail_ids_str){
			$subsql .= " AND t2.service_order_detail_id IN (".$service_order_detail_ids_str.")";
		}
        $sql="SELECT t1.id,t1.name 
		FROM user AS t1 INNER JOIN user_wise_service_order AS t2 ON t1.id=t2.user_id
        WHERE t1.status!='2' $subsql GROUP BY t1.id ORDER BY CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, t1.id DESC";        

        $query = $this->client_db->query($sql,false);        
        // return $last_query = $this->client_db->last_query();die();
        //return $query->result_array();  
        $arr = array();
		if($query){
			if($query->num_rows())
			{	
				$i=1;
				foreach($query->result() as $row)
				{
					$text='';	        	
					$text .=$row->name.'';  
					$arr[] = array(
								'text'=>$text,
								'id'=>$row->id,
								'a_attr'=>array('data-name'=>$row->name),
								'state'=>array('selected'=>true),
								'children'=>$this->get_user_tree_list($row->id,$i,$user_type,$service_order_detail_ids_str)
								);
					$i++;
				}
			} 
		}         
        return $arr;   
    }

	function get_manager_tree_list($user_id=0,$level=0,$user_type='user',$service_order_detail_ids_str='')
    {
       	
        $result = array();  
        $subsql = '';
        if($level==0)
        {
			if($user_id){
				$subsql .= " AND t1.id='".$user_id."'";
			}
        	
        }
        else
        {
        	$subsql .= " AND t1.manager_id='".$user_id."'";			
        }
		if($service_order_detail_ids_str){
			$subsql .= " AND t2.service_order_detail_id IN (".$service_order_detail_ids_str.")";			
		}
        $sql="SELECT t1.id,t1.name 
		FROM user AS t1 INNER JOIN user_wise_service_order AS t2 ON t1.id=t2.user_id
        WHERE t1.status!='2' $subsql GROUP BY t1.id ORDER BY CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, t1.id DESC";        

        $query = $this->client_db->query($sql,false);        
        // return $last_query = $this->client_db->last_query();die();
        //return $query->result_array();  
        $arr = array();
		
		if($query){
			if($query->num_rows())
			{
				$i=1;
				foreach($query->result() as $row)
				{
					// ======================
					// is manager check
					$sql2="SELECT user.id FROM user INNER JOIN user_wise_service_order ON user.id=user_wise_service_order.user_id WHERE user.manager_id='".$row->id."'"; 
					$query2 = $this->client_db->query($sql2,false);  
					if($query2->num_rows()>0){
						$this->is_manager='Y';
						array_push($this->manager_id_arr,$row->id);
					}
					else{
						$this->is_manager='N';
					}
					// is manager check
					// ======================
					// $text='';	        	
					// $text .=$row->name.'';  
					// $arr[] = array(
					// 			'text'=>$text.'-'.$this->is_manager,
					// 			'id'=>$row->id,
					// 			'is_manager'=>$this->is_manager,
					// 			'a_attr'=>array('data-name'=>$row->name,'data-is_manager'=>$this->is_manager),
					// 			'state'=>array('selected'=>true),
					// 			'children'=>$this->get_manager_tree_list($row->id,$i,$user_type,$service_order_detail_ids_str)
					// 			);
					$this->get_manager_tree_list($row->id,$i,$user_type,$service_order_detail_ids_str);
					$i++;
				}
			} 
		}         
        // return $arr;  
		return $this->manager_id_arr; 
    }

	function get_self_and_under_employee_ids($user_id=0,$level=0,$client_info=array(),$service_order_detail_ids_str='')
    {       
		
        $result = array();  
        $subsql = '';        
		if($user_id){
			$subsql .= " AND t1.id='".$user_id."'";
		}        
		$innersql="";
        //$subsql .= " AND t1.manager_id='".$user_id."'";
        if($service_order_detail_ids_str){
			$subsql .= " AND t2.service_order_detail_id IN (".$service_order_detail_ids_str.")";
			$innersql .=" INNER JOIN user_wise_service_order AS t2 ON t1.id=t2.user_id";
		}
        $sql="SELECT t1.id FROM user AS t1  $innersql
		WHERE t1.status!='2' $subsql  GROUP BY t1.id ORDER BY CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, t1.id DESC";        
		
        $query = $this->client_db->query($sql,false); 
        if($query){
			if($query->num_rows())
			{
				$i=1;        	
				foreach($query->result() as $row)
				{
					$this->get_employee_nth_level($row->id,$client_info,$service_order_detail_ids_str);
					$i++;
				}
			} 
		}               
        return array_keys($this->user_arr);
    }

	function get_employee_nth_level($user_id=0,$client_info,$service_order_detail_ids_str)
    {      	
		
        $result = array();  
		$innersql='';
        $subsql = '';        
        $subsql .= " AND t1.manager_id='".$user_id."'";      
		if($service_order_detail_ids_str){
			$subsql .= " AND t2.service_order_detail_id IN (".$service_order_detail_ids_str.")";
			$innersql .=" INNER JOIN user_wise_service_order AS t2 ON t1.id=t2.user_id";
		}  
        $sql="SELECT t1.id FROM user AS t1 $innersql WHERE t1.status!='2' $subsql GROUP BY t1.id ORDER BY t1.id DESC";       

        $query = $this->client_db->query($sql,false);        
        //return $last_query = $this->client_db->last_query();
        //return $query->result_array();  
        $tmp_arr = array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result() as $row)
				{
					$this->user_arr[$row->id] = $this->get_employee_nth_level($row->id,$client_info,$service_order_detail_ids_str);
				}
			} 
		}
         
    }

	function get_csv_list($argument=NULL)
    {       
        $result = array();        
        $subsql = '';   
		$subsqlInner='';
		$subsqlHaving='';       
        $order_by_str = " ORDER BY lead.id DESC ";
        // ---------------------------------------
        // SEARCH VALUE 
		if($argument['lead_ids'])
		{
			$subsql.=" AND lead.id IN (".$argument['lead_ids'].")";				
		}	
				
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
			GROUP_CONCAT(DISTINCT CONCAT(tagged_ps.id,'#',tagged_ps.name)) AS tagged_ps			
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
			LEFT JOIN lead_wise_product_service AS tagged_ps ON tagged_ps.lead_id=lead.id $subsqlInner WHERE lead.status='1' $subsql GROUP BY lead.id $subsqlHaving $order_by_str";
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

	function get_call_history_report_detail_csv_list($argument=NULL)
    {       
        $result = array();        
        $subsql = '';   
		$subsqlInner='';
		$group_by_str=" GROUP BY t1.id";
        $order_by_str = " ORDER BY t1.call_start DESC ";
        

        // ---------------------------------------
        // SEARCH VALUE 
		

        if($argument['ids']!='')
		{
			$subsql.=" AND t1.id IN (".$argument['ids'].")";
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
			t1.status_wise_msg,
			t1.created_at,
			t2.name AS assigned_user_name,
			c_paying_info.* 			 
			FROM tbl_call_history_for_lead_tmp AS t1 
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

	public function get_leads_by_source($arg)
	{
		$subsql='';
		if($arg['filter_selected_user_id']){
			$subsql .=" AND lead.assigned_user_id IN (".$arg['filter_selected_user_id'].")";
		}
		$sql="SELECT source.id AS source_id,source.name AS source_name,source.alias_name AS source_alias_name,COUNT(lead.source_id) AS lead_count FROM source 
			INNER JOIN lead ON source.id=lead.source_id WHERE 1=1 $subsql GROUP BY lead.source_id";		
		$query = $this->client_db->query($sql,false); 
		if($query){
			return $query->result_array();

		} else {
			return array();
		}
	}

	public function get_lead_lost_reasons($arg)
	{
		$subsql='';
		if($arg['filter_selected_user_id']){
			$subsql .=" AND lead.assigned_user_id IN (".$arg['filter_selected_user_id'].")";
		}
		$sql="SELECT current_stage_wise_msg AS lost_reason,COUNT(lead.current_stage_id) AS lead_lost_count FROM lead  WHERE lead.current_stage_id IN (3,5,6,7) $subsql GROUP BY lead.current_stage_id";		
		$query = $this->client_db->query($sql,false); 
		if($query){
			return $query->result_array();

		} else {
			return array();
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
		// 		$subsql.=" AND (lead.enquiry_date BETWEEN '".$start_date."' AND '".$end_date."')";
		// 	}
			
		// }
		// else
		// {
		// 	if($argument['filter_date_range_user_define_from']!='' && $argument['filter_date_range_user_define_to']!='')
		// 	{
		// 		$start_date=date_display_format_to_db_format($argument['filter_date_range_user_define_from']);
		// 		$end_date=date_display_format_to_db_format($argument['filter_date_range_user_define_to']);
		// 		$subsql.=" AND (lead.enquiry_date BETWEEN '".$start_date."' AND '".$end_date."')";
		// 	}
		// }	

		$sql="SELECT user.name AS assigned_user_name,
		lead.assigned_user_id,
		COUNT(lead.current_stage_id) AS lead_count
		FROM lead 
		INNER JOIN user ON lead.assigned_user_id=user.id
		WHERE lead.current_stage_id IN (3,5,6,7) $subsql GROUP BY lead.assigned_user_id";
		// echo $sql;die();
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
		
	}

}

?>