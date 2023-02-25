<?php
class Cronjobs_model extends CI_Model
{
    private $client_db = '';
    private $fsas_db = '';
	function __construct() {
        parent::__construct();
		// $this->load->database();        
        $this->user_arr=array(); 
        $this->client_db = $this->load->database('client', TRUE);
        $this->fsas_db = $this->load->database('default', TRUE);
    }
    public function initialise($client_info=array())
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

	public function conn_close()
    {
        $this->client->close();
        $this->default->close();
    }

    function update_stage_and_status($client_info)
    { 
        $config['hostname'] = DB_HOSTNAME;
        $config['username'] = $client_info->db_username;
        $config['password'] = $client_info->db_password;
        $config['database'] = $client_info->db_name;
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci'; 
        //$this->load->database($config,'');
        $this->client_db=$this->load->database($config,TRUE);
        // return $this->client_db->database;
        // $data=array('name'=>$client_info->db_name,'msg'=>'Action name : update_stage_and_status');
        // if($this->client_db->insert('test',$data))
        // {
        //    return $this->client_db->insert_id();
        // }
        // else
        // {
        //   return false;
        // }
        
        //=====================================================================
        $sql_setting="SELECT is_cronjobs_auto_regretted_on,auto_regretted_day_interval FROM company_setting WHERE id='1'";        
        $query_setting=$this->client_db->query($sql_setting);
        $row_setting=$query_setting->row_array();
        
        $is_cronjobs_auto_regretted_on=$row_setting['is_cronjobs_auto_regretted_on'];
        $auto_regretted_day_interval=($row_setting['auto_regretted_day_interval'])?$row_setting['auto_regretted_day_interval']:'10';
        //echo $is_cronjobs_auto_regretted_on;
        //echo'<br>';
        //echo $auto_regretted_day_interval;
        //die();
        //=====================================================================
        if($is_cronjobs_auto_regretted_on=='Y')
        {
    	    // STAGE "PENDING(1) OR PROSPECT(8)" IN LAST 10 DAYS CHANGED TO "AUTO-REGRETTED(6)" AND STATUS CHANGED TO "NOT FOLLOWED"
    	    $sql="SELECT t1.id,
            DATEDIFF(CURDATE(),t1.followup_date) AS last_followup_day_cnt  FROM lead AS t1 
    	    WHERE t1.current_stage_id IN ('1','8')";        
            $query  = $this->client_db->query($sql);
            //echo $this->client_db->last_query(); die();
            $result=array();
            //echo $query->num_rows();
            if($query->num_rows() > 0)
            {
                $flag=0;
                foreach ($query->result_array() as $row) 
                {		
                    if($row['last_followup_day_cnt']>$auto_regretted_day_interval)
                    {	
                        $lead_id=$row['id'];                     
                        $lead_regret_reason='Not followed in last '.$auto_regretted_day_interval.' days.';
                        // UPDATE LEAD STAGE/STATUS
                        $update_lead_data = array(
                            'current_stage_id' =>'6',
                            'current_stage' =>'AUTO-REGRETTED',
                            'current_stage_wise_msg' =>$lead_regret_reason,
                            'current_status_id'=>'4',
                            'current_status'=>'NOT FOLLOWED',
                            'is_hotstar'=>'N',
                            'modify_date'=>date("Y-m-d")
                        );  
                        $this->client_db->where('id',$lead_id);
                        if($this->client_db->update('lead',$update_lead_data))
                        {
                            $flag=0;
                        }
                        else
                        {
                            $flag=1;
                        }                            
                   
                        if($flag==0)
                        {
                            // Insert Stage Log
                            $stage_post_data=array(
                                    'lead_id'=>$lead_id,
                                    'stage_id'=>'6',
                                    'stage'=>'AUTO-REGRETTED',
                                    'stage_wise_msg'=>$lead_regret_reason,
                                    'create_datetime'=>date("Y-m-d H:i:s")
                                );
                            $this->client_db->insert('lead_stage_log',$stage_post_data);

                            // Insert Status Log
                            $status_post_data=array(
                                    'lead_id'=>$lead_id,
                                    'status_id'=>'4',
                                    'status'=>'NOT FOLLOWED',
                                    'create_datetime'=>date("Y-m-d H:i:s")
                                );
                            $this->client_db->insert('lead_status_log',$status_post_data);
			
                        }
                    
                    }
                }
            }        
    	    //echo $sql;
            // STAGE "QUOTED(2) OR NEGOTIATION(9)" IN LAST 15 DAYS CHANGED TO "AUTO DEAL LOST" AND STATUS CHANGED TO "COLD"
            /*
            $sql2="SELECT t1.id,
            DATEDIFF(CURDATE(),t1.followup_date) AS last_followup_day_cnt  FROM lead AS t1 
            WHERE t1.current_stage_id IN ('2')";        
            $query2  = $this->client_db->query($sql2);        
            $result=array();        
            if($query2->num_rows() > 0)
            {
                foreach ($query2->result_array() as $row) 
                { 
                    if($row['last_followup_day_cnt']>15)
                    {
                        $lead_id=$row['id'];
                    
                        $lead_regret_reason='Not followed in last 15 days.';
                        // UPDATE LEAD STAGE/STATUS
                        $update_lead_data = array(
                            'current_stage_id' =>'7',
                            'current_stage' =>'AUTO DEAL LOST',
                            'current_stage_wise_msg' =>$lead_regret_reason,
                            'current_status_id'=>'3',
                            'current_status'=>'COLD',
                            'is_hotstar'=>'N',
                            'modify_date'=>date("Y-m-d")
                        );  
                        $this->client_db->where('id',$lead_id);
                        if($this->client_db->update('lead',$update_lead_data))
                        {
                            $flag=0;
                        }
                        else
                        {
                            $flag=1;
                        }                            
                    
                        if($flag==0)
                        {
                            // Insert Stage Log
                            $stage_post_data=array(
                                    'lead_id'=>$lead_id,
                                    'stage_id'=>'7',
                                    'stage'=>'AUTO DEAL LOST',
                                    'stage_wise_msg'=>$lead_regret_reason,
                                    'create_datetime'=>date("Y-m-d H:i:s")
                                );
                            $this->client_db->insert('lead_stage_log',$stage_post_data);

                            // Insert Status Log
                            $status_post_data=array(
                                    'lead_id'=>$lead_id,
                                    'status_id'=>'3',
                                    'status'=>'COLD',
                                    'create_datetime'=>date("Y-m-d H:i:s")
                                );
                            $this->client_db->insert('lead_status_log',$status_post_data);
                        }
                    
                    }
                }
            }
            */
        }
        
    }

    public function update_graph_table()
	{
        $this->update_graph_lead_health_anlys();
        $this->update_graph_lead_revenue_growth();
        $this->update_graph_lead_source_anlys();
        $this->update_graph_lead_to_conversion();
        $this->update_graph_lead_lost_reason();
    }

    private function update_graph_lead_health_anlys()
	{
        $subsql="";
        $sql="SELECT MONTH(`create_date`) AS month, 
        count(*) AS total,
        sum(case when `current_stage_id`=4 then 1 else 0 end) as won, 
        sum(case when `current_stage_id`=5 then 1 else 0 end) as lost,
        sum(case when `current_stage_id`=3 then 1 else 0 end) as regretted
        FROM lead WHERE $subsql `current_stage_id`!=0
        GROUP BY month";
        $result=$this->client_db->query($sql);
        if($result->num_rows()){
            $data=$result->result();
            $arr=array();
            foreach ($data as $row) {
                $temp_arr=array(
                    'month'=>$row->month,
                    'total'=>$row->total,
                    'won'=>$row->won,
                    'lost'=>$row->lost,
                    'regretted'=>$row->regretted
                );
                $arr[]=$temp_arr;
            }
            $this->client_db->update_batch('graph_lead_health_anlys', $arr,'month');
        }
    }

    private function update_graph_lead_revenue_growth()
	{
        $subsql="";
        $sql = "SELECT MONTH(`create_date`) AS month, sum(case when `current_stage_id`=4 then 1 else 0 end) as won, sum(case when rev.`total_revenue_count` then rev.`total_revenue_count` else 0 end) as revenue FROM lead AS l INNER JOIN source AS s ON l.source_id=s.id LEFT JOIN ( SELECT SUM(opportunity_product.price) AS total_revenue_count,lead_opportunity.lead_id FROM lead_opportunity INNER JOIN lead_opportunity_wise_po ON lead_opportunity.id=lead_opportunity_wise_po.lead_opportunity_id INNER JOIN opportunity_product ON opportunity_product.opportunity_id=lead_opportunity.id ) AS rev ON rev.lead_id=l.id AND l.current_stage_id='4' WHERE l.status='1' $subsql GROUP BY month";
        $result=$this->client_db->query($sql);
        if($result->num_rows()){
            $data=$result->result();
            $arr=array();
            foreach ($data as $row) {
                $avg=($row->won?(float)$row->revenue/$row->won:0);
                $temp_arr=array(
                    'month'=>$row->month,
                    'won'=>$row->won,
                    'revenue'=>$row->revenue,
                    'avg_order_value'=>$avg
                );
                $arr[]=$temp_arr;
            }
            echo $this->client_db->update_batch('graph_lead_revenue_growth', $arr,'month');
        }
    }


    private function update_graph_lead_source_anlys()
	{
        $subsql="";
        $sql = "SELECT s.id AS source, SUM(if(current_stage_id=4,1,0)) AS won,SUM(if(current_stage_id=5,1,0)) AS lost,SUM(if(current_stage_id=1,1,0)) AS in_process,if(rev.total_revenue_count,rev.total_revenue_count,0) AS revenue FROM lead AS l
            INNER JOIN source AS s ON l.source_id=s.id 
            LEFT JOIN (
                SELECT SUM(opportunity_product.price) AS total_revenue_count,lead_opportunity.lead_id FROM lead_opportunity 
                INNER JOIN lead_opportunity_wise_po ON lead_opportunity.id=lead_opportunity_wise_po.lead_opportunity_id     
                INNER JOIN opportunity_product ON opportunity_product.opportunity_id=lead_opportunity.id        
            ) AS rev ON rev.lead_id=l.id AND l.current_stage_id='4'
        WHERE l.status='1' $subsql GROUP BY l.source_id";
        $result=$this->client_db->query($sql);
        if($result->num_rows()){
            $data=$result->result();
            /*echo '<pre>';
            print_r($data);die;*/
            $arr=array();
            foreach ($data as $row) {
                $temp_arr=array(
                    'source'=>$row->source,
                    'won'=>$row->won,
                    'lost'=>$row->lost,
                    'in_process'=>$row->in_process,
                    'revenue'=>$row->revenue
                );
                $arr[]=$temp_arr;
            }
            echo $this->client_db->update_batch('graph_lead_source_anlys', $arr,'source');
        }
    }

    private function update_graph_lead_to_conversion()
	{
        $subsql="";
        $sql="SELECT COUNT(*) AS count,s.id AS source FROM lead AS l
        INNER JOIN source AS s ON l.source_id=s.id WHERE l.status='1' $subsql GROUP BY l.source_id";
        $result=$this->client_db->query($sql);
        if($result->num_rows()){
            $data=$result->result();
            $arr=array();
            foreach ($data as $row) {
                $temp_arr=array(
                    'source'=>$row->source,
                    'count'=>$row->count
                );
                $arr[]=$temp_arr;
            }
            echo $this->client_db->update_batch('graph_lead_to_conversion', $arr,'source');
        }
    }

    private function update_graph_lead_lost_reason()
	{
        $subsql="";
        $sql = "SELECT r.id AS reason, count(l.id) AS count FROM lead AS l
        INNER JOIN opportunity_regret_reason AS r ON l.lost_reason=r.id 
        GROUP BY l.lost_reason";
        $result=$this->client_db->query($sql);
        if($result->num_rows()){
            $data=$result->result();
           /* echo '<pre>';
            print_r($data);die;*/
            $arr=array();
            foreach ($data as $row) {
                $temp_arr=array(
                    'reason'=>$row->reason,
                    'count'=>$row->count
                );
                $arr[]=$temp_arr;
            }
            echo $this->client_db->update_batch('graph_lead_lost_reason', $arr,'reason');
        }
    }
	
	// =======================================================================
	// DASHBOARD SUMMARY REPORT
	function update_dashboard_summery_count_report($client_info=array())
	{		
        $config['hostname'] = DB_HOSTNAME;
        $config['username'] = $client_info->db_username;
        $config['password'] = $client_info->db_password;
        $config['database'] = $client_info->db_name;
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci'; 
        //$this->load->database($config,'');
        $this->client_db=$this->load->database($config,TRUE);
		$company=get_company_profile($client_info);
		$company_country_id=$company['country_id'];
		$today_date=date("Y-m-d");
			
		// $sql="SELECT COUNT(lead_opportunity.lead_id) AS proposal, lead.id, lead.title, 
		// lead.customer_id, lead.assigned_user_id, lead.enquiry_date, lead.followup_date, 
		// lead.modify_date, lead.current_stage_id, lead.current_stage, lead.current_status, 
		// lead.is_hotstar, user.name AS assigned_user_name, cus.id AS cus_id, 
		// cus.first_name AS cus_first_name, cus.last_name AS cus_last_name, 
		// cus.mobile AS cus_mobile, cus.email AS cus_email, 
		// cus.company_name AS cus_company_name, countries.name AS cust_country_name, 
		// source.name AS source_name FROM lead 
		// INNER JOIN customer AS cus ON cus.id=lead.customer_id 
		// INNER JOIN source ON source.id=lead.source_id 
		// INNER JOIN user ON user.id=lead.assigned_user_id 
		// LEFT JOIN lead_opportunity ON lead_opportunity.lead_id=lead.id 
		// LEFT JOIN countries ON countries.id=cus.country_id 
		// WHERE lead.status='1' AND lead.is_hotstar='Y' 
		// GROUP BY lead.id ORDER BY lead.id DESC";

        // ------------------------------------
        // ACTIVE LEADS IDS
        $sql="SELECT id FROM opportunity_stage 
            WHERE id NOT IN ('3','4','5','6','7')";
            $result=$this->client_db->query($sql);
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
        // ACTIVE LEADS IDS
        // ------------------------------------
        


		$sql = "SELECT 
                group_concat(lead.id) AS total_lead_ids,
                lead.assigned_user_id,
				COUNT(lead.id) AS lead_count,
				SUM(if($active_lead_count_str,1,0)) AS active_lead_count,
				SUM(if(lead.current_stage_id=2,1,0)) AS quoted_lead_count,
				SUM(if(lead.current_stage_id!=2 && ($active_lead_count_str),1,0)) AS pending_lead_count,
                SUM(if(lead.current_stage_id=1,1,0)) AS new_lead_count,
				SUM(if(cus.country_id!='".$company_country_id."' && ($active_lead_count_str),1,0)) AS foreign_lead_count,
				SUM(if(cus.country_id='".$company_country_id."' && ($active_lead_count_str),1,0)) AS domestic_lead_count,
				SUM(if(lead.is_hotstar='Y' AND lead.status='1' AND ($active_lead_count_str),1,0)) AS star_marked_lead_count,
				SUM(if(lead.followup_date<='".$today_date."' AND lead.status='1' AND ($active_lead_count_str),1,0)) AS un_followed_lead_count
				FROM lead 
				INNER JOIN customer AS cus ON cus.id=lead.customer_id
				WHERE lead.status='1' GROUP BY lead.assigned_user_id";
		// echo $sql;die();
		$query=$this->client_db->query($sql);
		$total_inserted_row=0;
		$total_updated_row=0;
		if($query->num_rows())
		{			
			foreach ($query->result() as $row) 
			{
                $subsql=" SELECT 
                            COUNT(lead.id)
                            FROM lead 
                            INNER JOIN customer AS cus ON cus.id=lead.customer_id 
                            INNER JOIN source ON source.id=lead.source_id 
                            INNER JOIN user ON user.id=lead.assigned_user_id 
                            INNER JOIN lead_stage_log AS stage_log_inner ON stage_log_inner.lead_id=lead.id 
                            AND stage_log_inner.stage_id IN (2) 
                            WHERE lead.status='1' 
                            AND lead.assigned_user_id IN (".$row->assigned_user_id.") 
                            AND lead.current_stage_id IN (".$active_lead_ids.") 
                            GROUP BY lead.id";
                $subquery=$this->client_db->query($subsql);
                $quoted_lead_count=$subquery->num_rows();
                //$non_quoted_lead_count=($row->active_lead_count-$quoted_lead_count);
                
                
				//print_r($row);
				//echo '<br>';
				$sql_check = "SELECT id FROM dashboard_summery_count_report WHERE assigned_user_id='".$row->assigned_user_id."'";
				$query_check  = $this->client_db->query($sql_check);				
				if($query_check->num_rows()==0)
				{
					$data=array(
							'assigned_user_id'=>$row->assigned_user_id,
							'lead_count'=>$row->lead_count,
							'active_lead_count'=>$row->active_lead_count,
							'quoted_lead_count'=>$quoted_lead_count,
							//'pending_lead_count'=>$row->pending_lead_count,
                            'pending_lead_count'=>$row->new_lead_count,
							'foreign_lead_count'=>$row->foreign_lead_count,
							'domestic_lead_count'=>$row->domestic_lead_count,
							'star_marked_lead_count'=>$row->star_marked_lead_count,
							'un_followed_lead_count'=>$row->un_followed_lead_count,
							'created_at'=>date("Y-m-d H:i:s")
							);
					$this->client_db->insert('dashboard_summery_count_report',$data);
					$total_inserted_row++;
				}
				else
				{
					$assigned_user_id=$row->assigned_user_id;
					$data=array(
							'lead_count'=>$row->lead_count,
							'active_lead_count'=>$row->active_lead_count,
							'quoted_lead_count'=>$quoted_lead_count,
							//'pending_lead_count'=>$row->pending_lead_count,
                            'pending_lead_count'=>$row->new_lead_count,
							'foreign_lead_count'=>$row->foreign_lead_count,
							'domestic_lead_count'=>$row->domestic_lead_count,
							'star_marked_lead_count'=>$row->star_marked_lead_count,
							'un_followed_lead_count'=>$row->un_followed_lead_count,
							'updated_at'=>date("Y-m-d H:i:s")
							);
					$this->client_db->where('assigned_user_id',$assigned_user_id);
					$this->client_db->update('dashboard_summery_count_report',$data);
					$total_updated_row++;
				}
			}
		}		
		echo 'dashboard_summery_count_report: Total Inserted Records: '.$total_inserted_row.' | Total Updated Records: '.$total_updated_row;
	}
	
	function update_dashboard_day_wise_sales_report($client_info)
    {       
        $config['hostname'] = DB_HOSTNAME;
        $config['username'] = $client_info->db_username;
        $config['password'] = $client_info->db_password;
        $config['database'] = $client_info->db_name;
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';         
        $this->client_db=$this->load->database($config,TRUE);

        $company=get_company_profile($client_info);
        $company_country_id=$company['country_id'];
        $today_date=date("Y-m-d");        
        $day_before = date( 'Y-m-d', strtotime( $today_date . ' -1 day' ) );
            
        /*$sql = "SELECT DISTINCT get_date,assigned_user_id
                  FROM (
                      SELECT  create_date AS get_date,assigned_user_id FROM lead WHERE assigned_user_id>0 AND status='1'
                          UNION 
                      SELECT modify_date AS get_date,assigned_user_id FROM lead WHERE assigned_user_id>0 AND status='1'
                  ) AS alldate
              WHERE get_date IS NOT NULL AND get_date>='".$day_before."' ORDER BY get_date";*/

        $sql="SELECT 
				DATE(create_date) AS get_date,
				user_id AS assigned_user_id
				FROM lead_comment 
				WHERE user_id>0 AND DATE(create_date)>='".$day_before."' 
				GROUP BY DATE(create_date),user_id 
				ORDER BY DATE(create_date) ASC,user_id ASC";
        //echo $sql;die();
        $query=$this->client_db->query($sql);
        $total_inserted_row=0;
        $total_updated_row=0;
        if($query->num_rows())
        {           
            foreach ($query->result() as $row) 
            {
                //echo $row->get_date;
                //echo'<br>';
                
                $sql2="SELECT 
                lead.id,
                lead.assigned_user_id,
                lead.create_date,
                lead.modify_date,  
                group_concat(concat(lead.current_stage_id,'#',lead.id)),
                COUNT(DISTINCT lead.id) AS new_lead_count_old,                
				COUNT(DISTINCT (if(lead.create_date='".$row->get_date."' AND lead.assigned_user_id='".$row->assigned_user_id."',lead.id,NULL))) AS new_lead_count,
				GROUP_CONCAT(DISTINCT (if(lead.create_date='".$row->get_date."' AND lead.assigned_user_id='".$row->assigned_user_id."',lead.id,''))) AS new_lead_ids,
                COUNT(distinct if(lead.current_stage_id=1 AND lead.assigned_user_id='".$row->assigned_user_id."',concat(lead.current_stage_id,'#',lead.id),NULL)) AS pending_lead_count,
                COUNT(distinct if(lead.current_stage_id=8 AND lead.assigned_user_id='".$row->assigned_user_id."',concat(lead.current_stage_id,'#',lead.id),NULL)) AS prospect_lead_count, 
                COUNT(distinct if(lead.current_stage_id=2 AND lead.assigned_user_id='".$row->assigned_user_id."',concat(lead.current_stage_id,'#',lead.id),NULL)) AS quoted_lead_count,        
                COUNT(distinct if(lead.current_stage_id=9 AND lead.assigned_user_id='".$row->assigned_user_id."',concat(lead.current_stage_id,'#',lead.id),NULL)) AS negotiation_lead_count,
                COUNT(distinct if(lead.current_stage_id=3 AND lead.assigned_user_id='".$row->assigned_user_id."',concat(lead.current_stage_id,'#',lead.id),NULL)) AS regretted_lead_count,
                COUNT(distinct if(lead.current_stage_id=6 AND lead.assigned_user_id='".$row->assigned_user_id."',concat(lead.current_stage_id,'#',lead.id),NULL)) AS auto_regretted_lead_count,
                COUNT(distinct if(lead.current_stage_id=5 AND lead.assigned_user_id='".$row->assigned_user_id."',concat(lead.current_stage_id,'#',lead.id),NULL)) AS deal_lost_lead_count,
                COUNT(distinct if(lead.current_stage_id=7 AND lead.assigned_user_id='".$row->assigned_user_id."',concat(lead.current_stage_id,'#',lead.id),NULL)) AS auto_deal_lost_lead_count,
                COUNT(distinct if(lead.current_stage_id=4 AND lead.assigned_user_id='".$row->assigned_user_id."',concat(lead.current_stage_id,'#',lead.id),NULL)) AS deal_won_lead_count,


                l_update_count.*,
                SUM(DISTINCT lo.revenue_tmp) AS revenue,
                GROUP_CONCAT(DISTINCT lo.revenue_wise_currency_tmp) AS revenue_wise_currency
                FROM lead 
                LEFT JOIN 
                ( 
                    SELECT T1.lead_id, 
                       T1.deal_value, 
                       T2.code, 
                       SUM(DISTINCT T1.deal_value_as_per_purchase_order) AS revenue_tmp, 
                       GROUP_CONCAT(DISTINCT CONCAT(T1.deal_value_as_per_purchase_order,'#',T2.code)) AS revenue_wise_currency_tmp, 
                       T1.status,T1.modify_date AS lo_date,T3.assigned_user_id AS lo_user_assign_id
                       FROM lead_opportunity AS T1 
                       INNER JOIN currency AS T2 ON T2.id=T1.currency_type
                       INNER JOIN lead AS T3 ON T3.id=T1.lead_id 
                       INNER JOIN lead_opportunity_wise_po AS T4 ON T1.id=T4.lead_opportunity_id
                       WHERE T4.create_date='".$row->get_date."' AND T1.status='4' AND T3.assigned_user_id='".$row->assigned_user_id."'
                       GROUP BY T1.lead_id
                ) AS lo ON lo.lo_user_assign_id=lead.assigned_user_id
                LEFT JOIN 
                (
                    SELECT COUNT(DISTINCT lead_id) AS updated_lead_count,
					GROUP_CONCAT(DISTINCT lead_id) AS updated_lead_ids,
                    DATE(create_date) AS l_c_cd,
					user_id AS lc_user_id
                    FROM lead_comment 
                    WHERE  (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%') 
					AND DATE(create_date)='".$row->get_date."' 
					AND user_id='".$row->assigned_user_id."' GROUP BY DATE(create_date)
                ) AS l_update_count ON l_update_count.lc_user_id=lead.assigned_user_id
                WHERE (lead.modify_date='".$row->get_date."' OR lead.create_date='".$row->get_date."')";               
                
                
                //echo $sql2; die();
                $query2=$this->client_db->query($sql2);
                if($query2->num_rows())
                {           
                    foreach ($query2->result() as $row2) 
                    {
                        //if($row2->id>0)
                        if($row2->new_lead_count>0 || isset($row2->updated_lead_count) || $row2->quoted_lead_count>0 || $row2->pending_lead_count>0 || $row2->regretted_lead_count>0 || $row2->deal_lost_lead_count>0 || $row2->deal_won_lead_count>0 || $row2->auto_regretted_lead_count>0 || $row2->auto_deal_lost_lead_count>0)
                        {
                            $sql_check="SELECT id FROM dashboard_day_wise_sales_report WHERE assigned_user_id='".$row->assigned_user_id."' AND date='".$row->get_date."'";
                            // echo $sql_check; die();
                            $query_check=$this->client_db->query($sql_check);
                            $query_check_count=$query_check->num_rows();
                            //$query_check_count=0;
                            if($query_check_count==0)
                            {
                                $data=array(
                                        'assigned_user_id'=>$row->assigned_user_id,
                                        'date'=>$row->get_date,
                                        'new_lead_count'=>$row2->new_lead_count,
                                        'updated_lead_count'=>isset($row2->updated_lead_count)?$row2->updated_lead_count:0,
                                        'quoted_lead_count'=>$row2->quoted_lead_count,
                                        'pending_lead_count'=>$row2->pending_lead_count,
                                        'regretted_lead_count'=>$row2->regretted_lead_count,
                                        'deal_lost_lead_count'=>$row2->deal_lost_lead_count,
                                        'deal_won_lead_count'=>$row2->deal_won_lead_count,
                                        'revenue'=>$row2->revenue,
                                        'revenue_wise_currency'=>$row2->revenue_wise_currency,
                                        'auto_regretted_lead_count'=>$row2->auto_regretted_lead_count,
                                        'auto_deal_lost_lead_count'=>$row2->auto_deal_lost_lead_count,
                                        'created_at'=>date("Y-m-d H:i:s")
                                        );
                                $this->client_db->insert('dashboard_day_wise_sales_report',$data);
                                $total_inserted_row++;
                            }
                            else
                            {
                                $row_check=$query_check->row();
                                $id=$row_check->id;
                                $data=array(                            
                                        'new_lead_count'=>$row2->new_lead_count,
                                        'updated_lead_count'=>isset($row2->updated_lead_count)?$row2->updated_lead_count:0,
                                        'quoted_lead_count'=>$row2->quoted_lead_count,
                                        'pending_lead_count'=>$row2->pending_lead_count,
                                        'regretted_lead_count'=>$row2->regretted_lead_count,
                                        'deal_lost_lead_count'=>$row2->deal_lost_lead_count,
                                        'deal_won_lead_count'=>$row2->deal_won_lead_count,
                                        'revenue'=>$row2->revenue,
                                        'revenue_wise_currency'=>$row2->revenue_wise_currency,
                                        'auto_regretted_lead_count'=>$row2->auto_regretted_lead_count,
                                        'auto_deal_lost_lead_count'=>$row2->auto_deal_lost_lead_count,
                                        'updated_at'=>date("Y-m-d H:i:s")
                                        );
                                $this->client_db->where('id',$id);
                                $this->client_db->update('dashboard_day_wise_sales_report',$data);
                                $total_updated_row++;
                            }
                        }                        
                    }
                }
            }
        }       
        echo 'dashboard_day_wise_sales_report : Total Inserted Records: '.$total_inserted_row.' | Total Updated Records: '.$total_updated_row;
    }
	
	function update_dashboard_day_wise_sales_report__old__2022_01_27($client_info)
    {       
        $config['hostname'] = DB_HOSTNAME;
        $config['username'] = $client_info->db_username;
        $config['password'] = $client_info->db_password;
        $config['database'] = $client_info->db_name;
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';         
        $this->client_db=$this->load->database($config,TRUE);

        $company=get_company_profile($client_info);
        $company_country_id=$company['country_id'];
        $today_date=date("Y-m-d");        
        $day_before = date( 'Y-m-d', strtotime( $today_date . ' -1 day' ) );
            
        $sql = "SELECT DISTINCT get_date,assigned_user_id
                  FROM (
                      SELECT  create_date AS get_date,assigned_user_id FROM lead WHERE assigned_user_id>0 AND status='1'
                          UNION 
                      SELECT modify_date AS get_date,assigned_user_id FROM lead WHERE assigned_user_id>0 AND status='1'
                  ) AS alldate
              WHERE get_date IS NOT NULL AND get_date>='".$day_before."' ORDER BY get_date";

        /*$sql="SELECT 
				DATE(create_date) AS get_date,
				user_id AS assigned_user_id
				FROM lead_comment 
				WHERE (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%') 
				AND user_id>0 AND DATE(create_date)>='".$day_before."' 
				GROUP BY DATE(create_date),user_id 
				ORDER BY DATE(create_date) ASC,user_id ASC";*/
        //echo $sql;die();
        $query=$this->client_db->query($sql);
        $total_inserted_row=0;
        $total_updated_row=0;
        if($query->num_rows())
        {           
            foreach ($query->result() as $row) 
            {
                //echo $row->get_date;
                //echo'<br>';
                
                $sql2="SELECT 
                lead.id,
                lead.assigned_user_id,
                lead.create_date,
                lead.modify_date,  
                group_concat(concat(lead.current_stage_id,'#',lead.id)),
                COUNT(DISTINCT lead.id) AS new_lead_count_old,
                SUM(if(lead.create_date='".$row->get_date."' AND lead.assigned_user_id='".$row->assigned_user_id."',1,0)) AS new_lead_count,
                COUNT(distinct if(lead.current_stage_id=1,concat(lead.current_stage_id,'#',lead.id),NULL)) AS pending_lead_count,
                COUNT(distinct if(lead.current_stage_id=8,concat(lead.current_stage_id,'#',lead.id),NULL)) AS prospect_lead_count, 
                COUNT(distinct if(lead.current_stage_id=2,concat(lead.current_stage_id,'#',lead.id),NULL)) AS quoted_lead_count,        
                COUNT(distinct if(lead.current_stage_id=9,concat(lead.current_stage_id,'#',lead.id),NULL)) AS negotiation_lead_count,
                COUNT(distinct if(lead.current_stage_id=3,concat(lead.current_stage_id,'#',lead.id),NULL)) AS regretted_lead_count,
                COUNT(distinct if(lead.current_stage_id=6,concat(lead.current_stage_id,'#',lead.id),NULL)) AS auto_regretted_lead_count,
                COUNT(distinct if(lead.current_stage_id=5,concat(lead.current_stage_id,'#',lead.id),NULL)) AS deal_lost_lead_count,
                COUNT(distinct if(lead.current_stage_id=7,concat(lead.current_stage_id,'#',lead.id),NULL)) AS auto_deal_lost_lead_count,
                COUNT(distinct if(lead.current_stage_id=4,concat(lead.current_stage_id,'#',lead.id),NULL)) AS deal_won_lead_count,


                l_update_count.*,
                SUM(DISTINCT lo.revenue_tmp) AS revenue,
                GROUP_CONCAT(DISTINCT lo.revenue_wise_currency_tmp) AS revenue_wise_currency
                FROM lead 
                LEFT JOIN 
                ( 
                    SELECT T1.lead_id, T1.deal_value, T2.code, 
                       SUM(DISTINCT T1.deal_value) AS revenue_tmp, 
                       GROUP_CONCAT(DISTINCT CONCAT(T1.deal_value,'#',T2.code)) AS revenue_wise_currency_tmp, 
                       T1.status,T1.modify_date AS lo_date,T3.assigned_user_id AS lo_user_assign_id
                       FROM lead_opportunity AS T1 
                       INNER JOIN currency AS T2 ON T2.id=T1.currency_type
                       INNER JOIN lead AS T3 ON T3.id=T1.lead_id
                       WHERE T1.modify_date='".$row->get_date."' AND T1.status='4' AND T3.assigned_user_id='".$row->assigned_user_id."'
                       GROUP BY T1.lead_id
                ) AS lo ON lo.lo_user_assign_id=lead.assigned_user_id
                LEFT JOIN 
                (
                    SELECT COUNT(DISTINCT lead_id) AS updated_lead_count,
					lead_id AS lid,
                    DATE(create_date) AS l_c_cd,
					user_id AS lc_user_id
                    FROM lead_comment 
                    WHERE  (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%') 
					AND DATE(create_date)='".$row->get_date."' 
					AND user_id='".$row->assigned_user_id."' GROUP BY DATE(create_date)
                ) AS l_update_count ON l_update_count.lc_user_id=lead.assigned_user_id
                WHERE lead.assigned_user_id='".$row->assigned_user_id."' 
				AND (lead.modify_date='".$row->get_date."' OR lead.create_date='".$row->get_date."')";


                
                
                
                 //echo $sql2; die();
                $query2=$this->client_db->query($sql2);
                if($query2->num_rows())
                {           
                    foreach ($query2->result() as $row2) 
                    {
                        //if($row2->id>0)
                        {
                            $sql_check="SELECT id FROM dashboard_day_wise_sales_report WHERE assigned_user_id='".$row->assigned_user_id."' AND date='".$row->get_date."'";
                            // echo $sql_check; die();
                            $query_check=$this->client_db->query($sql_check);
                            $query_check_count=$query_check->num_rows();
                            //$query_check_count=0;
                            if($query_check_count==0)
                            {
                                $data=array(
                                        'assigned_user_id'=>$row->assigned_user_id,
                                        'date'=>$row->get_date,
                                        'new_lead_count'=>$row2->new_lead_count,
                                        'updated_lead_count'=>isset($row2->updated_lead_count)?$row2->updated_lead_count:0,
                                        'quoted_lead_count'=>$row2->quoted_lead_count,
                                        'pending_lead_count'=>$row2->pending_lead_count,
                                        'regretted_lead_count'=>$row2->regretted_lead_count,
                                        'deal_lost_lead_count'=>$row2->deal_lost_lead_count,
                                        'deal_won_lead_count'=>$row2->deal_won_lead_count,
                                        'revenue'=>$row2->revenue,
                                        'revenue_wise_currency'=>$row2->revenue_wise_currency,
                                        'auto_regretted_lead_count'=>$row2->auto_regretted_lead_count,
                                        'auto_deal_lost_lead_count'=>$row2->auto_deal_lost_lead_count,
                                        'created_at'=>date("Y-m-d H:i:s")
                                        );
                                $this->client_db->insert('dashboard_day_wise_sales_report',$data);
                                $total_inserted_row++;
                            }
                            else
                            {
                                $row_check=$query_check->row();
                                $id=$row_check->id;
                                $data=array(                            
                                        'new_lead_count'=>$row2->new_lead_count,
                                        'updated_lead_count'=>isset($row2->updated_lead_count)?$row2->updated_lead_count:0,
                                        'quoted_lead_count'=>$row2->quoted_lead_count,
                                        'pending_lead_count'=>$row2->pending_lead_count,
                                        'regretted_lead_count'=>$row2->regretted_lead_count,
                                        'deal_lost_lead_count'=>$row2->deal_lost_lead_count,
                                        'deal_won_lead_count'=>$row2->deal_won_lead_count,
                                        'revenue'=>$row2->revenue,
                                        'revenue_wise_currency'=>$row2->revenue_wise_currency,
                                        'auto_regretted_lead_count'=>$row2->auto_regretted_lead_count,
                                        'auto_deal_lost_lead_count'=>$row2->auto_deal_lost_lead_count,
                                        'updated_at'=>date("Y-m-d H:i:s")
                                        );
                                $this->client_db->where('id',$id);
                                $this->client_db->update('dashboard_day_wise_sales_report',$data);
                                $total_updated_row++;
                            }
                        }                        
                    }
                }
            }
        }       
        echo 'dashboard_day_wise_sales_report : Total Inserted Records: '.$total_inserted_row.' | Total Updated Records: '.$total_updated_row;
    }

    function update_dashboard_day_wise_product_report($client_info)
    {       
        $config['hostname'] = DB_HOSTNAME;
        $config['username'] = $client_info->db_username;
        $config['password'] = $client_info->db_password;
        $config['database'] = $client_info->db_name;
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';         
        $this->client_db=$this->load->database($config,TRUE);

        // $company=get_company_profile();
        // $company_country_id=$company['country_id'];
        $today_date=date("Y-m-d");        
        $day_before = date( 'Y-m-d', strtotime( $today_date . ' -1 day' ) );
            
        $sql = "SELECT DISTINCT get_date,assigned_user_id,name,(replace(search_name,'\'','')) AS search_name
                  FROM (
                      SELECT  t1.create_date AS get_date,t1.assigned_user_id,name,replace(LOWER(name) , ' ','') AS search_name 
                      FROM lead AS t1 INNER JOIN lead_wise_product_service AS t2 ON t1.id=t2.lead_id WHERE t1.status='1'
                          UNION 
                      SELECT modify_date AS get_date,a1.assigned_user_id,name,replace(LOWER(name) , ' ','') AS search_name FROM lead AS a1 INNER JOIN lead_wise_product_service AS a2 ON a1.id=a2.lead_id WHERE a1.status='1'
                  ) AS alldate
              WHERE get_date IS NOT NULL AND get_date>='".$day_before."' ORDER BY get_date";

        // $sql = "SELECT DISTINCT get_date,assigned_user_id
        //             FROM (
        //                 SELECT  create_date AS get_date,assigned_user_id FROM lead WHERE assigned_user_id>0 AND status='1'
        //                     UNION 
        //                 SELECT modify_date AS get_date,assigned_user_id FROM lead WHERE assigned_user_id>0 AND status='1'
        //             ) AS alldate
        //         WHERE get_date IS NOT NULL 
        //         ORDER BY get_date";
        // echo $sql;die();
        $query=$this->client_db->query($sql);
        $total_inserted_row=0;
        $total_updated_row=0;
        if($query->num_rows())
        {           
            foreach ($query->result() as $row) 
            {
                //echo $row->get_date;
                //echo'<br>';
                $sql2="SELECT 
                lead.id,
                lead.assigned_user_id,
                lead.create_date,
                lead.modify_date,
                group_concat(concat(lead.current_stage_id,'#',lead.id)),
                group_concat(concat(lead.current_stage_id,'#',lead_wise_p.lead_id)),
                count(distinct lead_wise_p.lead_id AND lead.create_date='".$row->get_date."') AS total_lead_count11,
                count(DISTINCT lead_wise_p.lead_id) AS total_lead_count,
                group_concat(DISTINCT lead_wise_p.lead_id) AS total_leads,

                COUNT(distinct if(lead.current_stage_id=1,concat(lead.current_stage_id,'#',lead_wise_p.lead_id),NULL)) AS pending_lead_count,
                COUNT(distinct if(lead.current_stage_id=8,concat(lead.current_stage_id,'#',lead_wise_p.lead_id),NULL)) AS prospect_lead_count, 
                COUNT(distinct if(lead.current_stage_id=2,concat(lead.current_stage_id,'#',lead_wise_p.lead_id),NULL)) AS quoted_lead_count,        
                COUNT(distinct if(lead.current_stage_id=9,concat(lead.current_stage_id,'#',lead_wise_p.lead_id),NULL)) AS negotiation_lead_count,
                COUNT(distinct if(lead.current_stage_id=3,concat(lead.current_stage_id,'#',lead_wise_p.lead_id),NULL)) AS regretted_lead_count,
                COUNT(distinct if(lead.current_stage_id=6,concat(lead.current_stage_id,'#',lead_wise_p.lead_id),NULL)) AS auto_regretted_lead_count,
                COUNT(distinct if(lead.current_stage_id=5,concat(lead.current_stage_id,'#',lead_wise_p.lead_id),NULL)) AS deal_lost_lead_count,
                COUNT(distinct if(lead.current_stage_id=7,concat(lead.current_stage_id,'#',lead_wise_p.lead_id),NULL)) AS auto_deal_lost_lead_count,
                COUNT(distinct if(lead.current_stage_id=4,concat(lead.current_stage_id,'#',lead_wise_p.lead_id),NULL)) AS deal_won_lead_count,
                l_update_count.*                
                FROM lead 
                INNER JOIN lead_wise_product_service AS lead_wise_p ON lead.id=lead_wise_p.lead_id AND lead.create_date='".$row->get_date."'
                LEFT JOIN customer AS cus ON cus.id=lead.customer_id
                LEFT JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id AND DATE(stage_log.create_datetime)='".$row->get_date."'
                LEFT JOIN 
                (
                    SELECT COUNT(DISTINCT lead_id) AS updated_lead_count,lead_id AS lid,
                    DATE(create_date) AS l_c_cd
                    FROM lead_comment 
                    WHERE   (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%')
                        GROUP BY DATE(create_date)
                ) AS l_update_count ON lead.modify_date=l_update_count.l_c_cd
                WHERE (replace(LOWER(lead_wise_p.name) , ' ',''))='".$row->search_name."' AND lead.assigned_user_id='".$row->assigned_user_id."' AND lead.create_date='".$row->get_date."' GROUP BY (replace(LOWER(lead_wise_p.name) , ' ','')),lead.assigned_user_id,lead.create_date";
                // echo $sql2; die();
                $query2=$this->client_db->query($sql2);
                if($query2->num_rows())
                {           
                    foreach ($query2->result() as $row2) 
                    {
                        $sql_check = "SELECT id FROM dashboard_day_wise_product_report WHERE replace(LOWER(product_name) , ' ','')='".$row->search_name."' AND assigned_user_id='".$row->assigned_user_id."' AND date='".$row->get_date."'";
                        $query_check  = $this->client_db->query($sql_check);
                        $query_check_count=$query_check->num_rows();
                        //$query_check_count=0;
                        //$total_lead_count=COUNT(explode(",", $row2->total_lead_count));
                        $total_lead_count=$row2->total_lead_count;
                        if($query_check_count==0)
                        {
                            $data=array(
                                    'product_name'=>$row->name,
                                    'assigned_user_id'=>$row->assigned_user_id,
                                    'date'=>$row->get_date,
                                    'total_lead_count'=>$total_lead_count,
                                    'pending_lead_count'=>$row2->pending_lead_count,
                                    'prospect_lead_count'=>$row2->prospect_lead_count,
                                    'quoted_lead_count'=>$row2->quoted_lead_count,
                                    'negotiation_lead_count'=>$row2->negotiation_lead_count,
                                    'regretted_lead_count'=>$row2->regretted_lead_count,
                                    'auto_regretted_lead_count'=>$row2->auto_regretted_lead_count,
                                    'deal_lost_lead_count'=>$row2->deal_lost_lead_count,
                                    'auto_deal_lost_lead_count'=>$row2->auto_deal_lost_lead_count,
                                    'deal_won_lead_count'=>$row2->deal_won_lead_count,
                                    'inserted_at'=>date("Y-m-d H:i:s"),
                                    'updated_at'=>date("Y-m-d H:i:s")
                                    );
                            
                            $this->client_db->insert('dashboard_day_wise_product_report',$data);
                            $total_inserted_row++;
                        }
                        else
                        {
                            $row_check=$query_check->row();
                            $id=$row_check->id;
                            $data=array(
                                    'total_lead_count'=>$total_lead_count,
                                    'pending_lead_count'=>$row2->pending_lead_count,
                                    'prospect_lead_count'=>$row2->prospect_lead_count,
                                    'quoted_lead_count'=>$row2->quoted_lead_count,
                                    'negotiation_lead_count'=>$row2->negotiation_lead_count,
                                    'regretted_lead_count'=>$row2->regretted_lead_count,
                                    'auto_regretted_lead_count'=>$row2->auto_regretted_lead_count,
                                    'deal_lost_lead_count'=>$row2->deal_lost_lead_count,
                                    'auto_deal_lost_lead_count'=>$row2->auto_deal_lost_lead_count,
                                    'deal_won_lead_count'=>$row2->deal_won_lead_count,
                                    'inserted_at'=>date("Y-m-d H:i:s"),
                                    'updated_at'=>date("Y-m-d H:i:s")
                                    );
                            $this->client_db->where('id',$id);
                            $this->client_db->update('dashboard_day_wise_product_report',$data);
                            $total_updated_row++;
                        }
                    }
                }
            }
        }       
        echo 'dashboard_product_vs_lead_report : Total Inserted Records: '.$total_inserted_row.' | Total Updated Records: '.$total_updated_row;
    }
    

    function update_dashboard_day_wise_source_report($client_info)
    {       
        $config['hostname'] = DB_HOSTNAME;
        $config['username'] = $client_info->db_username;
        $config['password'] = $client_info->db_password;
        $config['database'] = $client_info->db_name;
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';         
        $this->client_db=$this->load->database($config,TRUE);

        // $company=get_company_profile();
        // $company_country_id=$company['country_id'];
        $today_date=date("Y-m-d");        
        $day_before = date( 'Y-m-d', strtotime( $today_date . ' -1 day' ) );
            
        // $sql = "SELECT DISTINCT get_date,source_id
        //           FROM (
        //               SELECT  create_date AS get_date,source_id FROM lead WHERE source_id>0 AND status='1'
        //                   UNION 
        //               SELECT modify_date AS get_date,source_id FROM lead WHERE source_id>0 AND status='1'
        //           ) AS alldate
        //       WHERE get_date IS NOT NULL AND get_date>='".$day_before."' ORDER BY get_date ";

        // $sql = "SELECT create_date AS get_date,assigned_user_id,source_id
        //          FROM lead WHERE create_date IS NOT NULL AND create_date>='".$day_before."' GROUP BY source_id,assigned_user_id,create_date ORDER BY create_date ";

        $sql = "SELECT DISTINCT get_date,assigned_user_id,source_id
                  FROM (
                      SELECT  create_date AS get_date,assigned_user_id,source_id FROM lead WHERE assigned_user_id>0 AND source_id>0 AND status='1'
                          UNION 
                      SELECT modify_date AS get_date,assigned_user_id,source_id FROM lead WHERE assigned_user_id>0 AND source_id>0 AND status='1'
                  ) AS alldate
              WHERE get_date IS NOT NULL AND get_date>='".$day_before."' GROUP BY get_date,assigned_user_id,source_id ORDER BY get_date";

        // echo $sql;die();
        $query=$this->client_db->query($sql);
        $total_inserted_row=0;
        $total_updated_row=0;
        if($query->num_rows())
        {           
            foreach ($query->result() as $row) 
            {
                //echo $row->get_date;
                //echo'<br>';
                $sql2="SELECT 
                lead.id,
                lead.assigned_user_id,
                lead.create_date,
                lead.modify_date,    
                group_concat(concat(lead.current_stage_id,'#',lead.id)),
                SUM(if(lead.create_date='".$row->get_date."',1,0)) AS new_lead_count,
                count(DISTINCT lead.id) AS total_lead_count,
                 COUNT(distinct if(lead.current_stage_id=1,concat(lead.current_stage_id,'#',lead.id),NULL)) AS pending_lead_count,
                COUNT(distinct if(lead.current_stage_id=8,concat(lead.current_stage_id,'#',lead.id),NULL)) AS prospect_lead_count, 
                COUNT(distinct if(lead.current_stage_id=2,concat(lead.current_stage_id,'#',lead.id),NULL)) AS quoted_lead_count,        
                COUNT(distinct if(lead.current_stage_id=9,concat(lead.current_stage_id,'#',lead.id),NULL)) AS negotiation_lead_count,
                COUNT(distinct if(lead.current_stage_id=3,concat(lead.current_stage_id,'#',lead.id),NULL)) AS regretted_lead_count,
                COUNT(distinct if(lead.current_stage_id=6,concat(lead.current_stage_id,'#',lead.id),NULL)) AS auto_regretted_lead_count,
                COUNT(distinct if(lead.current_stage_id=5,concat(lead.current_stage_id,'#',lead.id),NULL)) AS deal_lost_lead_count,
                COUNT(distinct if(lead.current_stage_id=7,concat(lead.current_stage_id,'#',lead.id),NULL)) AS auto_deal_lost_lead_count,
                COUNT(distinct if(lead.current_stage_id=4,concat(lead.current_stage_id,'#',lead.id),NULL)) AS deal_won_lead_count,
                l_update_count.*,
                SUM(DISTINCT lo.revenue_tmp) AS revenue,
                GROUP_CONCAT(DISTINCT lo.revenue_wise_currency_tmp) AS revenue_wise_currency
                FROM lead 
                LEFT JOIN customer AS cus ON cus.id=lead.customer_id
                LEFT JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id AND DATE(stage_log.create_datetime)='".$row->get_date."'
                LEFT JOIN 
                (                    
                    SELECT T1.lead_id, T1.deal_value, T2.code, 
                       SUM(DISTINCT T1.deal_value) AS revenue_tmp, 
                       GROUP_CONCAT(DISTINCT CONCAT(T1.deal_value,'#',T2.code)) AS revenue_wise_currency_tmp, 
                       T1.status,T1.modify_date AS lo_date,
                       T3.source_id AS lo_source_id,
                       T3.assigned_user_id AS lo_user_assign_id
                       FROM lead_opportunity AS T1 
                       INNER JOIN currency AS T2 ON T2.id=T1.currency_type
                       INNER JOIN lead AS T3 ON T3.id=T1.lead_id
                       WHERE T1.modify_date='".$row->get_date."' AND T1.status='4' AND T3.source_id='".$row->source_id."' AND T3.assigned_user_id='".$row->assigned_user_id."'
                       GROUP BY T1.lead_id
                ) AS lo ON lo.lo_source_id=lead.source_id AND lo.lo_user_assign_id=lead.assigned_user_id
                LEFT JOIN 
                (
                    SELECT COUNT(DISTINCT lead_id) AS updated_lead_count,
                    T1.lead_id AS lid,
                    DATE(T1.create_date) AS l_c_cd,
                    T1.user_id AS lc_user_id,
                    T2.source_id AS lc_source_id
                    FROM lead_comment AS T1 
                    INNER JOIN lead AS T2 ON T2.id=T1.lead_id
                    WHERE  (replace(LOWER(T1.title) , ' ','') NOT LIKE '%newleadcreated%') AND DATE(T1.create_date)='".$row->get_date."' AND T1.user_id='".$row->assigned_user_id."' AND T2.source_id='".$row->source_id."'
                        GROUP BY DATE(T1.create_date)
                ) AS l_update_count ON l_update_count.lc_user_id=lead.assigned_user_id AND l_update_count.lc_source_id=lead.source_id
                WHERE lead.source_id='".$row->source_id."' AND lead.assigned_user_id='".$row->assigned_user_id."' AND (lead.modify_date='".$row->get_date."' OR lead.create_date='".$row->get_date."') GROUP BY lead.source_id,lead.assigned_user_id,lead.create_date";

                /*
                    $sql2="SELECT 
                lead.id,
                lead.assigned_user_id,
                lead.create_date,
                lead.modify_date,    
                group_concat(concat(lead.current_stage_id,'#',lead.id)),
                SUM(if(lead.create_date='".$row->get_date."',1,0)) AS new_lead_count,
                count(DISTINCT lead.id) AS total_lead_count,
                 COUNT(distinct if(lead.current_stage_id=1,concat(lead.current_stage_id,'#',lead.id),NULL)) AS pending_lead_count,
                COUNT(distinct if(lead.current_stage_id=8,concat(lead.current_stage_id,'#',lead.id),NULL)) AS prospect_lead_count, 
                COUNT(distinct if(lead.current_stage_id=2,concat(lead.current_stage_id,'#',lead.id),NULL)) AS quoted_lead_count,        
                COUNT(distinct if(lead.current_stage_id=9,concat(lead.current_stage_id,'#',lead.id),NULL)) AS negotiation_lead_count,
                COUNT(distinct if(lead.current_stage_id=3,concat(lead.current_stage_id,'#',lead.id),NULL)) AS regretted_lead_count,
                COUNT(distinct if(lead.current_stage_id=6,concat(lead.current_stage_id,'#',lead.id),NULL)) AS auto_regretted_lead_count,
                COUNT(distinct if(lead.current_stage_id=5,concat(lead.current_stage_id,'#',lead.id),NULL)) AS deal_lost_lead_count,
                COUNT(distinct if(lead.current_stage_id=7,concat(lead.current_stage_id,'#',lead.id),NULL)) AS auto_deal_lost_lead_count,
                COUNT(distinct if(lead.current_stage_id=4,concat(lead.current_stage_id,'#',lead.id),NULL)) AS deal_won_lead_count,
                l_update_count.*,
                SUM(DISTINCT lo.revenue_tmp) AS revenue,
                GROUP_CONCAT(DISTINCT lo.revenue_wise_currency_tmp) AS revenue_wise_currency
                FROM lead 
                LEFT JOIN customer AS cus ON cus.id=lead.customer_id
                LEFT JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id AND DATE(stage_log.create_datetime)='".$row->get_date."'
                LEFT JOIN 
                (                    
                    SELECT 
                    T1.lead_id,
                    T1.deal_value,
                    T2.code,
                    SUM(DISTINCT T1.deal_value) AS revenue_tmp,               
                    GROUP_CONCAT(DISTINCT CONCAT(T1.deal_value,'#',T2.code)) AS revenue_wise_currency_tmp,
                    T1.status
                    FROM lead_opportunity AS T1 
                    INNER JOIN currency AS T2 ON T2.id=T1.currency_type
                    WHERE T1.modify_date='".$row->get_date."' AND T1.status='4'
                    GROUP BY T1.lead_id
                ) AS lo ON lo.lead_id=lead.id
                LEFT JOIN 
                (
                    SELECT COUNT(DISTINCT lead_id) AS updated_lead_count,lead_id AS lid,
                    DATE(create_date) AS l_c_cd
                    FROM lead_comment 
                    WHERE   (replace(LOWER(title) , ' ','') NOT LIKE '%newleadcreated%') AND DATE(create_date)='".$row->get_date."'
                        GROUP BY DATE(create_date)
                ) AS l_update_count ON lead.modify_date=l_update_count.l_c_cd
                WHERE lead.source_id='".$row->source_id."' AND lead.assigned_user_id='".$row->assigned_user_id."' AND lead.create_date='".$row->get_date."' GROUP BY lead.source_id,lead.assigned_user_id,lead.create_date";
                 */
                // echo $sql2; die();
                $query2=$this->client_db->query($sql2);
                if($query2->num_rows())
                {           
                    foreach ($query2->result() as $row2) 
                    {
                        $sql_check = "SELECT id FROM dashboard_day_wise_source_report WHERE source_id='".$row->source_id."' AND assigned_user_id='".$row->assigned_user_id."' AND date='".$row->get_date."'";
                        // echo $sql_check; die();
                        $query_check  = $this->client_db->query($sql_check);
                        $query_check_count=$query_check->num_rows();
                        //$query_check_count=0;
                        if($query_check_count==0)
                        {
                            $data=array(
                                    'source_id'=>$row->source_id,
                                    'assigned_user_id'=>$row->assigned_user_id,
                                    'date'=>$row->get_date,
                                    'total_lead_count'=>$row2->total_lead_count,
                                    'updated_lead_count'=>isset($row2->updated_lead_count)?$row2->updated_lead_count:0,
                                    'quoted_lead_count'=>$row2->quoted_lead_count,
                                    'pending_lead_count'=>$row2->pending_lead_count,
                                    'prospect_lead_count'=>$row2->prospect_lead_count,
                                    'negotiation_lead_count'=>$row2->negotiation_lead_count,
                                    'regretted_lead_count'=>$row2->regretted_lead_count,
                                    'deal_lost_lead_count'=>$row2->deal_lost_lead_count,
                                    'deal_won_lead_count'=>$row2->deal_won_lead_count,
                                    'revenue'=>$row2->revenue,
                                    'revenue_wise_currency'=>$row2->revenue_wise_currency,
                                    'auto_regretted_lead_count'=>$row2->auto_regretted_lead_count,
                                    'auto_deal_lost_lead_count'=>$row2->auto_deal_lost_lead_count,
                                    'created_at'=>date("Y-m-d H:i:s")
                                    );
                            $this->client_db->insert('dashboard_day_wise_source_report',$data);
                            $total_inserted_row++;
                        }
                        else
                        {
                            $row_check=$query_check->row();
                            $id=$row_check->id;
                            $data=array(                            
                                    'total_lead_count'=>$row2->total_lead_count,
                                    'updated_lead_count'=>isset($row2->updated_lead_count)?$row2->updated_lead_count:0,
                                    'quoted_lead_count'=>$row2->quoted_lead_count,
                                    'pending_lead_count'=>$row2->pending_lead_count,
                                    'prospect_lead_count'=>$row2->prospect_lead_count,
                                    'negotiation_lead_count'=>$row2->negotiation_lead_count,
                                    'regretted_lead_count'=>$row2->regretted_lead_count,
                                    'deal_lost_lead_count'=>$row2->deal_lost_lead_count,
                                    'deal_won_lead_count'=>$row2->deal_won_lead_count,
                                    'revenue'=>$row2->revenue,
                                    'revenue_wise_currency'=>$row2->revenue_wise_currency,
                                    'auto_regretted_lead_count'=>$row2->auto_regretted_lead_count,
                                    'auto_deal_lost_lead_count'=>$row2->auto_deal_lost_lead_count,
                                    'updated_at'=>date("Y-m-d H:i:s")
                                    );
                            // print_r($data); die();
                            $this->client_db->where('id',$id);
                            $this->client_db->update('dashboard_day_wise_source_report',$data);
                            $total_updated_row++;
                        }
                    }
                }
            }
        }       
        echo 'dashboard_day_wise_source_report : Total Inserted Records: '.$total_inserted_row.' | Total Updated Records: '.$total_updated_row;
    }

    function update_dashboard_day_wise_lost_reason_report($client_info)
    {
        $config['hostname'] = DB_HOSTNAME;
        $config['username'] = $client_info->db_username;
        $config['password'] = $client_info->db_password;
        $config['database'] = $client_info->db_name;
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';         
        $this->client_db=$this->load->database($config,TRUE);

        $today_date=date("Y-m-d");        
        $day_before = date( 'Y-m-d', strtotime( $today_date . ' -1 day' ) );

        // $sql = "SELECT create_date AS get_date,source_id,lost_reason
        //          FROM lead WHERE create_date IS NOT NULL AND create_date>='".$day_before."' AND current_stage_id IN ('3','5') GROUP BY lost_reason,source_id,create_date ORDER BY create_date ";

        $sql = "SELECT DISTINCT get_date,lost_reason,source_id,assigned_user_id
                  FROM (
                      SELECT  create_date AS get_date,lost_reason,source_id,assigned_user_id FROM lead WHERE lost_reason>0 AND assigned_user_id>0 AND source_id>0 AND status='1' AND current_stage_id IN ('3','5')
                          UNION 
                      SELECT modify_date AS get_date,lost_reason,source_id,assigned_user_id FROM lead WHERE lost_reason>0 AND assigned_user_id>0 AND source_id>0 AND status='1' AND current_stage_id IN ('3','5')
                  ) AS alldate
              WHERE get_date IS NOT NULL AND get_date>='".$day_before."' GROUP BY get_date,assigned_user_id,source_id ORDER BY get_date";

        // echo $sql; die();
        $query=$this->client_db->query($sql);
        $total_inserted_row=0;
        $total_updated_row=0;
        if($query->num_rows())
        {           
            foreach ($query->result() as $row) 
            {
                //echo $row->get_date;
                //echo'<br>';
                $sql2="SELECT 
                lead.id,
                lead.assigned_user_id,
                lead.create_date,
                lead.modify_date,    
                group_concat(concat(lead.current_stage_id,'#',lead.id)),
                SUM(if(lead.create_date='".$row->get_date."',1,0)) AS new_lead_count,
                COUNT(DISTINCT lead.id) AS total_lead_count                 
                FROM lead 
                LEFT JOIN lead_stage_log AS stage_log ON stage_log.lead_id=lead.id AND DATE(stage_log.create_datetime)='".$row->get_date."'
                WHERE lead.lost_reason='".$row->lost_reason."' AND lead.source_id='".$row->source_id."' AND lead.assigned_user_id='".$row->assigned_user_id."' AND (lead.modify_date='".$row->get_date."' OR lead.create_date='".$row->get_date."') GROUP BY lead.lost_reason,lead.source_id,lead.assigned_user_id,lead.create_date";
                // echo $sql2; die();
                $query2=$this->client_db->query($sql2);
                if($query2->num_rows())
                {           
                    foreach ($query2->result() as $row2) 
                    {
                        $sql_check = "SELECT id FROM dashboard_day_wise_lost_reason_report WHERE reason_id='".$row->lost_reason."' AND source_id='".$row->source_id."' AND assigned_user_id='".$row->assigned_user_id."' AND date='".$row->get_date."'";
                        // echo $sql_check; die();
                        $query_check  = $this->client_db->query($sql_check);
                        $query_check_count=$query_check->num_rows();
                        //$query_check_count=0;
                        if($query_check_count==0)
                        {
                            $data=array(
                                    'reason_id'=>$row->lost_reason,
                                    'source_id'=>$row->source_id,
                                    'assigned_user_id'=>$row->assigned_user_id,
                                    'date'=>$row->get_date,
                                    'total_lead_count'=>$row2->total_lead_count,
                                    'updated_at'=>date("Y-m-d H:i:s"),
                                    'inserted_at'=>date("Y-m-d H:i:s")
                                    );
                            $this->client_db->insert('dashboard_day_wise_lost_reason_report',$data);
                            $total_inserted_row++;
                        }
                        else
                        {
                            $row_check=$query_check->row();
                            $id=$row_check->id;
                            $data=array(
                                    'total_lead_count'=>$row2->total_lead_count,
                                    'updated_at'=>date("Y-m-d H:i:s")
                                    );                           
                            // print_r($data); die();
                            $this->client_db->where('id',$id);
                            $this->client_db->update('dashboard_day_wise_lost_reason_report',$data);
                            $total_updated_row++;
                        }
                    }
                }
            }
        }       
        echo 'dashboard_day_wise_lost_reason_report : Total Inserted Records: '.$total_inserted_row.' | Total Updated Records: '.$total_updated_row;
    }
	// DASHBOARD SUMMARY REPORT
	// =======================================================================
	
	
	function get_won_products($month=1,$client_info)
	{		
        $config['hostname'] = DB_HOSTNAME;
        $config['username'] = $client_info->db_username;
        $config['password'] = $client_info->db_password;
        $config['database'] = $client_info->db_name;
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';         
        $this->client_db=$this->load->database($config,TRUE);

		// lead_opportunity
		// quotation
		// quotation_product
		// lead
		$sql="SELECT qp.id,
			p.name AS product_name,
			p.code AS product_code,
			p.price AS product_price,
			l.id AS lead_id,
			l.current_stage_id,
			l.current_stage,
			COUNT(lo.id) AS quotation_count,
			lo.id AS lo_id,
			group_concat(q.quote_no SEPARATOR '<br>') AS quote_no
			FROM quotation_product AS qp 
			INNER JOIN product_varient AS p ON p.id=qp.product_id
			INNER JOIN quotation AS q ON qp.quotation_id=q.id 
			INNER JOIN lead_opportunity AS lo ON lo.id=q.opportunity_id 
			INNER JOIN lead AS l ON lo.lead_id=l.id 
			WHERE lo.create_date>=date(NOW() - INTERVAL ".$month." MONTH) 
			AND l.current_stage_id='4'
			GROUP BY qp.product_id ORDER BY l.id DESC";
		$query=$this->client_db->query($sql);
		$total_inserted_row=0;
		$total_updated_row=0;
		$table ='';
		$table .='<table width="100%" border="1">';
		$table .='<tr style="background-color:#bca9fc">';
		$table .='<td><b>Product</b></td>';		
		$table .='<td align="center"><b>Quotation Count</b></td>';
		$table .='<td align="center"><b>Current Stage</b></td>';
		$table .='<td align="center"><b>Lead ID</b></td>';
		$table .='<td align="center"><b>Quote No</b></td>';		
		$table .='</tr>';
		if($query->num_rows())
		{			
			
			foreach ($query->result() as $row) 
			{
				$table .='<tr>';
				$table .='<td>'.$row->product_name.'('.$row->product_code.')</td>';
				$table .='<td align="center">'.$row->quotation_count.'</td>';
				$table .='<td align="center">'.$row->current_stage.'</td>';
				$table .='<td align="center">'.$row->lead_id.'</td>';
				$table .='<td align="center">'.$row->quote_no.'</td>';				
				$table .='</tr>';
			}
		}
		else
		{
			$table .='<tr>';
			$table .='<td colspan="5">No record found</td>';
			$table .='</tr>';
		}
		$table .='</table>';
		echo $table;
	}
	
	function get_products_wise_lead($month=1,$client_info=array())
	{		
        $config['hostname'] = DB_HOSTNAME;
        $config['username'] = $client_info->db_username;
        $config['password'] = $client_info->db_password;
        $config['database'] = $client_info->db_name;
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';         
        $this->client_db=$this->load->database($config,TRUE);

		// lead_opportunity
		// quotation
		// quotation_product
		// lead
		$sql="SELECT qp.id,
			p.name AS product_name,
			p.code AS product_code,
			p.price AS product_price,
			group_concat(DISTINCT l.id SEPARATOR '<br>') AS lead_id,
			COUNT(DISTINCT l.id) AS lead_count
			FROM quotation_product AS qp 
			INNER JOIN product_varient AS p ON p.id=qp.product_id
			INNER JOIN quotation AS q ON qp.quotation_id=q.id 
			INNER JOIN lead_opportunity AS lo ON lo.id=q.opportunity_id 
			INNER JOIN lead AS l ON lo.lead_id=l.id 
			WHERE lo.create_date>=date(NOW() - INTERVAL ".$month." MONTH)
			GROUP BY qp.product_id ORDER BY l.id DESC";
		$query=$this->client_db->query($sql);
		$total_inserted_row=0;
		$total_updated_row=0;
		$table ='';
		$table .='<table width="100%" border="1">';
		$table .='<tr style="background-color:#bca9fc">';
		$table .='<td><b>Product</b></td>';		
		$table .='<td align="center"><b>Lead Count</b></td>';
		$table .='<td align="center"><b>Lead IDs</b></td>';			
		$table .='</tr>';
		if($query->num_rows())
		{			
			
			foreach ($query->result() as $row) 
			{
				$table .='<tr>';
				$table .='<td>'.$row->product_name.'('.$row->product_code.')</td>';
				$table .='<td align="center">'.$row->lead_count.'</td>';
				$table .='<td align="center">'.$row->lead_id.'</td>';
				$table .='</tr>';
			}
		}
		else
		{
			$table .='<tr>';
			$table .='<td colspan="3">No record found</td>';
			$table .='</tr>';
		}
		$table .='</table>';
		echo $table;
	}

     

    public function set_daily_report($report_date,$client_info=array())
    {   
        // $config['hostname'] = DB_HOSTNAME;
        // $config['username'] = $client_info->db_username;
        // $config['password'] = $client_info->db_password;
        // $config['database'] = $client_info->db_name;
        // $config['dbdriver'] = 'mysqli';
        // $config['dbprefix'] = '';
        // $config['pconnect'] = FALSE;
        // $config['db_debug'] = TRUE;
        // $config['cache_on'] = FALSE;
        // $config['cachedir'] = '';
        // $config['char_set'] = 'utf8';
        // $config['dbcollat'] = 'utf8_general_ci';         
        // $this->client_db=$this->load->database($config,TRUE); 


        // table truncated
        $this->client_db->truncate('tbl_daily_report');
        $this->client_db->truncate('tbl_daily_report_stage_wise');


        $sql="SELECT 
        t1.enquiry_date AS report_date,
        t1.assigned_user_id,
        COUNT(DISTINCT t1.id) AS new_lead_count,
        SUM(
        CASE WHEN c_paying_info.custid REGEXP 'GROUP_CONCAT(DISTINCT t1.customer_id SEPARATOR '|')'
             THEN 1 
             ELSE 0 END
        ) AS paying_customer_new_lead_count_old,
        COUNT(DISTINCT 
        CASE WHEN c_paying_info.lid REGEXP 'GROUP_CONCAT(DISTINCT t1.id SEPARATOR '|')'
             THEN t1.id 
             END) AS paying_customer_new_lead_count,
        GROUP_CONCAT(DISTINCT t1.id) AS new_leads,
        GROUP_CONCAT(DISTINCT 
        CASE WHEN c_paying_info.lid REGEXP 'GROUP_CONCAT(DISTINCT t1.id SEPARATOR '|')'
             THEN t1.id 
             END
        ) AS paying_customer_new_leads            
        FROM lead AS t1 
        LEFT JOIN (
            SELECT 
            GROUP_CONCAT(DISTINCT lead.customer_id SEPARATOR ',') AS custid,
            GROUP_CONCAT(DISTINCT s_log.lead_id) AS lid            
            FROM lead 
            INNER JOIN lead_stage_log AS s_log 
            ON s_log.lead_id=lead.id AND s_log.stage_id='4' 
            GROUP BY s_log.stage_id 
        ) AS c_paying_info ON FIND_IN_SET (t1.customer_id,c_paying_info.custid)  
        WHERE t1.enquiry_date='".$report_date."' 
        GROUP BY t1.enquiry_date,t1.assigned_user_id 
        ORDER BY t1.id";
        //echo $sql;die();
        $total_new_leads_arr=array();
        $total_paying_new_leads_arr=array();
        $new_leads_arr=array();
        $query=$this->client_db->query($sql);
        if($query)
        { 
			if($query->num_rows())
			{
				foreach ($query->result_array() as $row) 
				{

					$data_post=array(
					'report_date'=>$row['report_date'],
					'assigned_user_id'=>$row['assigned_user_id'],
					'reserve_keyword'=>'new',
					'new_lead_count'=>$row['new_lead_count'],
					'new_leads'=>$row['new_leads'],
					'updated_lead_count'=>'',
					'updated_leads'=>'',
					'paying_customer_new_lead_count'=>$row['paying_customer_new_lead_count'],
					'paying_customer_new_leads'=>$row['paying_customer_new_leads'],
					'paying_customer_updated_lead_count'=>'',
					'paying_customer_updated_leads'=>'',
					'created_at'=>date("Y-m-d H:i:s")
					);
					$this->client_db->insert('tbl_daily_report',$data_post);

					$new_leads_arr[$row['report_date']][$row['assigned_user_id']]=array(
						'new_lead_count'=>$row['new_lead_count'],
						'paying_customer_new_lead_count'=>$row['paying_customer_new_lead_count'],
						'new_leads'=>$row['new_leads'],
						'paying_customer_new_leads'=>$row['paying_customer_new_leads'],

					);
					// ----------------------------------
					$tmp_arr=explode(",", $row['new_leads']);
					if(count($tmp_arr))
					{
						foreach($tmp_arr AS $lid)
						{
							array_push($total_new_leads_arr, $lid);
						}
					} 
					
					$tmp_p_arr=explode(",", $row['paying_customer_new_leads']);
					if(count($tmp_p_arr))
					{
						foreach($tmp_p_arr AS $p_lid)
						{
							array_push($total_paying_new_leads_arr, $p_lid);
						}
					} 
					// ----------------------------------
				}
			}            
        }
        // print_r($total_new_leads_arr);
        // print_r($total_paying_new_leads_arr);
        // print_r($new_leads_arr); die('new');
        // die();

        $sql_update="SELECT 
        DATE(t2.create_date) AS report_date,
        t1.assigned_user_id,
        COUNT(DISTINCT t2.lead_id) AS updated_lead_count, 
        SUM(
        CASE WHEN c_paying_info.lid REGEXP 'GROUP_CONCAT(DISTINCT t2.lead_id SEPARATOR '|')'
             THEN 1 
             ELSE 0 END
        ) AS paying_customer_updated_lead_count_old, 
        COUNT(DISTINCT 
        CASE WHEN c_paying_info.lid REGEXP 'GROUP_CONCAT(DISTINCT t2.lead_id SEPARATOR '|')'
             THEN t2.lead_id 
             END) AS paying_customer_updated_lead_count,
        GROUP_CONCAT(DISTINCT t2.lead_id) AS updated_leads,
        GROUP_CONCAT(DISTINCT 
        CASE WHEN c_paying_info.lid REGEXP 'GROUP_CONCAT(DISTINCT t2.lead_id SEPARATOR '|')'
             THEN t2.lead_id 
             END
        ) AS paying_customer_updated_leads,      
        GROUP_CONCAT(t1.id),
        GROUP_CONCAT(DISTINCT t1.customer_id SEPARATOR '|'),
        c_paying_info.*         
        FROM lead AS t1         
        INNER JOIN lead_comment AS t2 ON (t1.id=t2.lead_id AND t2.title NOT LIKE '%A New Lead Created%') 
        LEFT JOIN (
            SELECT 
            GROUP_CONCAT(DISTINCT lead.customer_id SEPARATOR ',') AS custid,
            GROUP_CONCAT(DISTINCT s_log.lead_id) AS lid            
            FROM lead 
            INNER JOIN lead_stage_log AS s_log 
            ON s_log.lead_id=lead.id AND s_log.stage_id='4' 
            GROUP BY s_log.stage_id 
        ) AS c_paying_info ON FIND_IN_SET (t1.customer_id,c_paying_info.custid)  
        WHERE DATE(t2.create_date)='".$report_date."' 
        GROUP BY DATE(t2.create_date),t1.assigned_user_id 
        ORDER BY t1.id";
        //echo $sql_update;die();
        $update_leads_arr=array();
        $query=$this->client_db->query($sql_update);
        $total_updated_leads_arr=array();
        $total_paying_updated_leads_arr=array();
        if($query)
        { 
			if($query->num_rows())
			{
				foreach ($query->result_array() as $row) 
				{
					$data_post=array(
					'report_date'=>$row['report_date'],
					'assigned_user_id'=>$row['assigned_user_id'],
					'reserve_keyword'=>'updated',
					'new_lead_count'=>'',
					'new_leads'=>'',
					'updated_lead_count'=>$row['updated_lead_count'],
					'updated_leads'=>$row['updated_leads'],
					'paying_customer_new_lead_count'=>'',
					'paying_customer_new_leads'=>'',
					'paying_customer_updated_lead_count'=>$row['paying_customer_updated_lead_count'],
					'paying_customer_updated_leads'=>$row['paying_customer_updated_leads'],
					'created_at'=>date("Y-m-d H:i:s")
					);
					$this->client_db->insert('tbl_daily_report',$data_post);

					$update_leads_arr[$row['report_date']][$row['assigned_user_id']]=array(
						'updated_lead_count'=>$row['updated_lead_count'],
						'paying_customer_updated_lead_count'=>$row['paying_customer_updated_lead_count'],
						'updated_leads'=>$row['updated_leads'],
						'paying_customer_updated_leads'=>$row['paying_customer_updated_leads'],
						
					);
					// ----------------------------------
					$tmp_arr=explode(",", $row['updated_leads']);
					if(count($tmp_arr))
					{
						foreach($tmp_arr AS $lid)
						{
							array_push($total_updated_leads_arr, $lid);
						}
					} 
					
					$tmp_p_arr=explode(",", $row['paying_customer_updated_leads']);
					if(count($tmp_p_arr))
					{
						foreach($tmp_p_arr AS $p_lid)
						{
							array_push($total_paying_updated_leads_arr, $p_lid);
						}
					} 
					// ----------------------------------

				}
			}
            
        }
        // print_r($update_leads_arr); 
        // print_r($total_updated_leads_arr);
        // print_r($total_paying_updated_leads_arr);
        // die("update");

        $all_leads_arr=array_unique(array_merge($total_new_leads_arr,$total_updated_leads_arr), SORT_REGULAR);

        $all_paying_leads_arr=array_unique(array_merge($total_paying_new_leads_arr,$total_paying_updated_leads_arr), SORT_REGULAR);

        // print_r($all_leads_arr);
        // print_r($all_paying_leads_arr);
        $total_updated_leads_str=implode(",", $total_updated_leads_arr);
        $total_paying_updated_leads_str=implode(",", $total_paying_updated_leads_arr);

        $daily_report_stage_wise=array();
        $sql_stage="SELECT id,name FROM opportunity_stage WHERE 1=1 ORDER BY sort";
        $query_stage=$this->client_db->query($sql_stage);
        if($query_stage)
        { 
			if($query_stage->num_rows())
			{
				foreach ($query_stage->result_array() as $row_stage)
				{
					
					$sql="SELECT 
					t1.*,
					DATE(t1.create_datetime) AS report_date,
					t2.assigned_user_id,
					GROUP_CONCAT(DISTINCT t1.lead_id SEPARATOR ',') updated_log_leads
					FROM lead_stage_log AS t1 
					INNER JOIN lead AS t2 ON t1.lead_id=t2.id
					WHERE t1.stage_id='".$row_stage['id']."' AND DATE(t1.create_datetime)='".$report_date."' GROUP BY DATE(t1.create_datetime),t2.assigned_user_id";
					$query=$this->client_db->query($sql);
					if($query->num_rows())
					{ 
						foreach ($query->result_array() as $row) 
						{
							// print_r($row);
							$updated_log_leads_arr=explode(",", $row['updated_log_leads']);
							$stage_wise_lead_count = array_intersect($all_leads_arr, $updated_log_leads_arr);


							$paying_customer_stage_wise_lead_count = array_intersect($all_paying_leads_arr, $updated_log_leads_arr);
						

							$data_post=array(
							'report_date'=>$row['report_date'],
							'assigned_user_id'=>$row['assigned_user_id'],
							'stage_id'=>$row_stage['id'],
							'stage_name'=>$row_stage['name'],
							'stage_wise_lead_count'=>count($stage_wise_lead_count),
							'stage_wise_leads'=>implode(',',$stage_wise_lead_count),
							'paying_customer_stage_wise_lead_count'=>count($paying_customer_stage_wise_lead_count),
							'paying_customer_stage_wise_leads'=>implode(',',$paying_customer_stage_wise_lead_count),
							'updated_log_leads'=>$row['updated_log_leads'],
							'created_at'=>date("Y-m-d H:i:s")
							);
							$this->client_db->insert('tbl_daily_report_stage_wise',$data_post);

							$daily_report_stage_wise[]=array(
								'report_date'=>$row['report_date'],
								'assigned_user_id'=>$row['assigned_user_id'],
								'stage_id'=>$row_stage['id'],
								'stage_name'=>$row_stage['name'],
								'stage_wise_lead_count'=>count($stage_wise_lead_count),
								'paying_customer_stage_wise_lead_count'=>count($paying_customer_stage_wise_lead_count)
							);
						}
					}                
				}
			}
            
        }
        // print_r($daily_report_stage_wise);
        // die('ok');
        return true;
    } 

    public function get_daily_report($assigned_user_id='',$client_info=array())
    {
        // $config['hostname'] = DB_HOSTNAME;
        // $config['username'] = $client_info->db_username;
        // $config['password'] = $client_info->db_password;
        // $config['database'] = $client_info->db_name;
        // $config['dbdriver'] = 'mysqli';
        // $config['dbprefix'] = '';
        // $config['pconnect'] = FALSE;
        // $config['db_debug'] = TRUE;
        // $config['cache_on'] = FALSE;
        // $config['cachedir'] = '';
        // $config['char_set'] = 'utf8';
        // $config['dbcollat'] = 'utf8_general_ci';         
        // $this->client_db=$this->load->database($config,TRUE); 

        $subsql="";
        if($assigned_user_id)
        {
            $subsql .=" AND assigned_user_id='".$assigned_user_id."'";
        }

        $sql="SELECT 
                SUM(new_lead_count) AS new_lead_count,
                SUM(paying_customer_new_lead_count) AS paying_customer_new_lead_count 
                FROM tbl_daily_report 
                WHERE reserve_keyword='new' $subsql 
                GROUP BY report_date";
        // echo $sql; die();
        $query=$this->client_db->query($sql);
        // echo $this->client_db->last_query(); die();
        // echo $query->num_rows(); die();
		if($query)
		{
			if($query->num_rows())
			{
				$row=$query->row_array();
				$new_leads=array(
					'new_lead_count'=>$row['new_lead_count'],
					'paying_customer_new_lead_count'=>$row['paying_customer_new_lead_count'],
				);
			}
			else
			{
				$new_leads=array(
					'new_lead_count'=>0,
					'paying_customer_new_lead_count'=>0,
				);
			}
		}
        


        $sql_update="SELECT 
                SUM(updated_lead_count) AS updated_lead_count,
                SUM(paying_customer_updated_lead_count) AS paying_customer_updated_lead_count 
                FROM tbl_daily_report 
                WHERE reserve_keyword='updated' $subsql 
                GROUP BY report_date";
        // echo $sql; die();
        $query_update=$this->client_db->query($sql_update);
        // echo $this->client_db->last_query(); die();
        // echo $query_update->num_rows(); die();
		if($query_update)
		{
			if($query_update->num_rows())
			{
				$row_update=$query_update->row_array();
				$updated_leads=array(
					'updated_lead_count'=>$row_update['updated_lead_count'],
					'paying_customer_updated_lead_count'=>$row_update['paying_customer_updated_lead_count'],
				);
			}
			else
			{
				$updated_leads=array(
					'updated_lead_count'=>0,
					'paying_customer_updated_lead_count'=>0,
				);
			}
		}
        


        $sql_stage="SELECT 
                stage_id,
                stage_name,
                SUM(stage_wise_lead_count) AS stage_wise_lead_count,
                SUM(paying_customer_stage_wise_lead_count) AS paying_customer_stage_wise_lead_count 
                FROM tbl_daily_report_stage_wise 
                WHERE 1=1 $subsql 
                GROUP BY report_date,stage_id ORDER BY id";
        // echo $sql; die();
        $query_stage=$this->client_db->query($sql_stage);
        // echo $this->client_db->last_query(); die();
        // echo $query_stage->num_rows(); die();
        $stage_wise_leads=array();
		if($query_stage)
		{
			if($query_stage->num_rows())
			{ 
				foreach ($query_stage->result_array() as $row_stage) 
				{
					$stage_wise_leads[]=array(
						'stage_id'=>$row_stage['stage_id'],
						'stage_name'=>$row_stage['stage_name'],
						'stage_wise_lead_count'=>$row_stage['stage_wise_lead_count'],
						'paying_customer_stage_wise_lead_count'=>$row_stage['paying_customer_stage_wise_lead_count'],
					);
				}
			}
		}
        
        

        // -------------------------------------
        // USER WISE
        $sql="SELECT 
                t1.assigned_user_id,
                SUM(t1.new_lead_count) AS new_lead_count,
                SUM(t1.paying_customer_new_lead_count) AS paying_customer_new_lead_count,
                user.name AS assigned_user_name
                FROM tbl_daily_report AS t1 
                INNER JOIN user ON user.id=t1.assigned_user_id 
                WHERE t1.reserve_keyword='new' $subsql 
                GROUP BY t1.report_date,t1.assigned_user_id";
        // echo $sql; die();
        $user_wise_new_leads=array();
        $query=$this->client_db->query($sql);  
		if($query)
		{
			if($query->num_rows())
			{            
				foreach ($query->result_array() as $row) 
				{
					$user_wise_new_leads[]=array(
						'assigned_user_id'=>$row['assigned_user_id'],
						'assigned_user_name'=>$row['assigned_user_name'],
						'new_lead_count'=>$row['new_lead_count'],
					);
				}
			}
		}      
        


        $sql="SELECT t1.report_date,t1.assigned_user_id FROM tbl_daily_report_stage_wise AS t1 WHERE 1=1 GROUP BY t1.report_date,t1.assigned_user_id";
        $query=$this->client_db->query($sql);
        $user_wise_stage=array();
		if($query)
		{
			if($query->num_rows())
			{ 
				foreach ($query->result_array() as $row) 
				{
					$sql_stage="SELECT 
					t1.assigned_user_id,
					t1.stage_id,
					t1.stage_name,
					SUM(t1.stage_wise_lead_count) AS stage_wise_lead_count,
					SUM(t1.paying_customer_stage_wise_lead_count) AS paying_customer_stage_wise_lead_count,
					user.name AS assigned_user_name 
					FROM tbl_daily_report_stage_wise AS t1 
					INNER JOIN user ON user.id=t1.assigned_user_id
					WHERE t1.report_date='".$row['report_date']."' AND t1.assigned_user_id='".$row['assigned_user_id']."' $subsql 
					GROUP BY t1.stage_id ORDER BY t1.id";
					$query_stage=$this->client_db->query($sql_stage);
					if($query_stage->num_rows())
					{ 
						foreach ($query_stage->result_array() as $row_stage) 
						{
							$user_wise_stage[$row['report_date']][$row['assigned_user_id']][]=array(
								'assigned_user_id'=>$row_stage['assigned_user_id'],
								'assigned_user_name'=>$row_stage['assigned_user_name'],
								'stage_id'=>$row_stage['stage_id'],
								'stage_name'=>$row_stage['stage_name'],
								'stage_wise_lead_count'=>$row_stage['stage_wise_lead_count'],
								'paying_customer_stage_wise_lead_count'=>$row_stage['paying_customer_stage_wise_lead_count'],
							);
						}
					}
				}
			}
		}
        
        //print_r($user_wise_stage); die();
        
        

        return array('new'=>$new_leads,'updated'=>$updated_leads,'stage_wise_count'=>$stage_wise_leads,'user_wise_new_leads'=>$user_wise_new_leads,'user_wise_stage'=>$user_wise_stage);
    }  
    
    function CreateCronTest($data)
    { 
        
        if($this->client_db->insert('cron_test',$data))
        {   
            $last_id=$this->client_db->insert_id();            
            return $last_id;
        }
        else
        {
          return false;
        }
    }


    // =======================================================================
    // Indiamart_model.php
    function im_insert($data,$client_info=array())
	{
		
		if($this->client_db->insert('indiamart_tmp',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}

    public function im_get_rows($im_s_id='',$client_info=array())
	{
		$subsql="";
		if($im_s_id)
		{
			$subsql .=" AND indiamart_setting_id='".$im_s_id."'";
		}
		$sql="SELECT id,
				indiamart_setting_id,
				RN,
				QUERY_ID,
				QTYPE,
				SENDERNAME,
				SENDEREMAIL,
				SUBJECT,
				DATE_RE,
				DATE_R,
				DATE_TIME_RE,
				GLUSR_USR_COMPANYNAME,
				READ_STATUS,
				SENDER_GLUSR_USR_ID,
				MOB,
				COUNTRY_FLAG,
				QUERY_MODID,
				LOG_TIME,
				QUERY_MODREFID,
				DIR_QUERY_MODREF_TYPE,
				ORG_SENDER_GLUSR_ID,
				ENQ_MESSAGE,
				ENQ_ADDRESS,
				ENQ_CALL_DURATION,
				ENQ_RECEIVER_MOB,
				ENQ_CITY,
				ENQ_STATE,
				PRODUCT_NAME,
				COUNTRY_ISO,
				EMAIL_ALT,
				MOBILE_ALT,
				PHONE,
				PHONE_ALT,
				IM_MEMBER_SINCE,
				TOTAL_COUNT,
				msg 
				FROM indiamart_tmp WHERE 1=1 $subsql ORDER BY indiamart_setting_id ASC,QUERY_ID ASC";
		$result=$this->client_db->query($sql);
		//return $result->result_array();

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

    public function im_update($data,$id,$client_info=array())
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('indiamart_tmp',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

    function im_truncate($client_info=array())
	{
		
		if($this->client_db->truncate('indiamart_tmp'))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}
    // Indiamart_model.php
    // ===========================================================================

    // ===========================================================================
    // Countries_model
    public function countries_get_country_by_iso($iso,$client_info=array())
	{		
		$sql="SELECT id,phonecode FROM countries WHERE sortname='".strtoupper($iso)."'";
		$query=$this->client_db->query($sql);	
		if($query->num_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}

	
    // Countries_model
    // ===========================================================================

    // ===========================================================================
    // Cities_model
    public function cities_get_city_id_by_name($name,$client_info=array())
	{		
		$sql="SELECT id,name,state_id FROM cities WHERE replace(LOWER(name),' ', '')='".str_replace(' ', '', strtolower(str_replace('\'', '',$name)))."'";
		$query=$this->client_db->query($sql);
		if($query){
			if($query->num_rows()>0)
			{
				return $query->row()->id;
			}
			else
			{
				return false;
			}
		}
		
	}
    public function cities_get_city_by_id($id,$client_info=array())
	{
		
		$sql="SELECT id,name,state_id FROM cities WHERE id='".$id."'";
		$query=$this->client_db->query($sql);	
		if($query->num_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return array();
		}
	}
    // Cities_model
    // ===========================================================================

    // ===========================================================================
    // States_model
    public function states_get_state_id_by_name($name,$client_info=array())
	{
		
		$sql="SELECT id,name,country_id FROM states WHERE replace(LOWER(name),' ', '')='".str_replace(' ', '', strtolower(str_replace('\'', '',$name)))."'";
		$query=$this->client_db->query($sql);
		if($query){
			if(isset($query->row()->id)){
				return $query->row()->id;
			}
			else{
				return false;
			}
			// if($query->num_rows()>0)
			// {
			// 	return $query->row()->id;
			// }
			// else
			// {
			// 	return false;
			// }
		}	
		else{
			return false;
		}		
	}
    // States_model
    // ===========================================================================

    // ===========================================================================
    // Source_model
    public function source_get_source_id_by_name($text,$client_info=array())
	{
		
		$sql="SELECT id FROM source WHERE replace(LOWER(name) , ' ','')='".$text."' LIMIT 1";
		$query=$this->client_db->query($sql);		
		if($query->num_rows()>0)
		{
			$row=$query->row();
			return $row->id;
		}
		else
		{
			return 0;
		}
	}
    function source_add($data,$client_info=array())
	{
		
		if($this->client_db->insert('source',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

    // Source_model
    // ===========================================================================

    // ===========================================================================
    // Indiamart_setting_model
    public function indiamart_setting_get_keyword_rule5_wise_assigned_user_id($arr,$client_info=array())
	{		
		$assign_rule=$arr['assign_rule'];
		$indiamart_setting_id=$arr['indiamart_setting_id'];
		$search_keyword=$arr['search_keyword'];
		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM indiamart_assign_rule_details WHERE im_setting_id='".$indiamart_setting_id."' AND assign_rule_id='".$assign_rule."'";
		$query = $this->client_db->query($sql,false);
		$return=array();
		if($query->num_rows())
		{
			foreach($query->result_array() AS $row)
			{
				$id=$row['id'];
				$find_to=unserialize($row['find_to']);
				$assign_to=unserialize($row['assign_to']);
				$assign_start=$row['assign_start'];	
				$assign_end=(count($assign_to)-1);
				if($find_to!='other')
				{         
					if(count($find_to))
					{
						for($i=0;$i<count($find_to);$i++)
						{        
							if(strpos($search_keyword, str_replace(' ', '', strtolower($find_to[$i]))) !== false)
							{					
								$assign_start++;
								if($assign_start>$assign_end)
								{
									$assign_start=0;
								}
								break 2;
							}        
						} 
					}
				}
				else if($find_to=='other')
				{
					$assign_start++;
					if($assign_start>$assign_end)
					{
						$assign_start=0;
					}  
					break;					   
				}
			}
			$post_data=array('assign_start'=>$assign_start);			
			$this->client_db->where('id',$id);
			$this->client_db->update('indiamart_assign_rule_details',$post_data);				
			return $assign_to[$assign_start];
		}
	}

    public function indiamart_setting_get_rule_wise_assigned_user_id($arr,$client_info=array())
	{		

		$assign_rule=$arr['assign_rule'];
		$indiamart_setting_id=$arr['indiamart_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM indiamart_assign_rule_details WHERE im_setting_id='".$indiamart_setting_id."' AND assign_rule_id='".$assign_rule."'";

		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows())
			{

				foreach($query->result_array() AS $row)
				{
					$id=$row['id'];
					$find_to=unserialize($row['find_to']);
					$assign_to=unserialize($row['assign_to']);
					$assign_start=$row['assign_start'];	
					$assign_end=(count($assign_to)-1);
					if(is_array($find_to) && in_array($search_keyword, $find_to)){
						if(in_array($search_keyword, $find_to))
						{
							//$tmp_assigned_user_id=$assign_to[$assign_start];
							$assign_start++;
							if($assign_start>$assign_end)
							{
								$assign_start=0;
							}

							// $post_data=array('assign_start'=>$assign_start);
							// $this->client_db->where('id',$id);
							// $this->client_db->update('indiamart_assign_rule_details',$post_data);

							// return $tmp_assigned_user_id;
							break;
						}
						else
						{
							if($find_to=='other')
							{
								//$tmp_assigned_user_id=$assign_to[$assign_start];
								$assign_start++;
								if($assign_start>$assign_end)
								{
									$assign_start=0;
								}

								// $post_data=array('assign_start'=>$assign_start);
								// $this->client_db->where('id',$id);
								// $this->client_db->update('indiamart_assign_rule_details',$post_data);

								// return $tmp_assigned_user_id;
								break;
							}
						}
					}					
				}

				$post_data=array('assign_start'=>$assign_start);
				// echo $id.':'.$assign_start.'<br>';
				// echo $assign_to[$assign_start].'<br>';
				$this->client_db->where('id',$id);
				$this->client_db->update('indiamart_assign_rule_details',$post_data);	
				// echo $last_query = $this->client_db->last_query();die();		
				return $assign_to[$assign_start];
			}
		}
		
	}

    public function indiamart_setting_EditIndiamartCredentials($data,$id,$client_info=array())
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('indiamart_setting',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}
    public function indiamart_setting_GetIndiamartCredentials($client_info=array())
	{
		

		// $sql="SELECT * FROM indiamart_setting";
		$sql="SELECT 
		t1.id,
		t1.is_old_version,
		t1.account_name,
		t1.glusr_mobile,
		t1.glusr_mobile_key,
		t1.assign_rule,
		t1.assign_to,
		t1.assign_start 		
		FROM indiamart_setting AS t1 ORDER BY t1.id ASC";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result_array() AS $row)
				{
					$assign_to_arr=array();
					if($row['assign_to']){
						$assign_to_arr=unserialize($row['assign_to']);
					}
					
					$assign_to_atr=='';
					if(count($assign_to_arr)){
						$assign_to_atr=implode(",",$assign_to_arr);
					}
					
					if($assign_to_atr)
					{
						$sql2="SELECT GROUP_CONCAT(name) AS assign_to_str from user WHERE id IN ($assign_to_atr)";
						$query2 = $this->client_db->query($sql2,false);
						$row2=$query2->row_array();
						$assign_to_name=$row2['assign_to_str'];					
					}
					else
					{
						$assign_to_name='--';
					}

					if(count($assign_to_arr))
					{
						if(in_array(0,$assign_to_arr))
						{
							if($assign_to_name=='--'){
								$assign_to_name ='Common Lead Pool';							
							}
							else{
								$assign_to_name .=',Common Lead Pool';
							}						
						}
					}
					
					
					$return[]=array(
							'id'=>$row['id'],
							'is_old_version'=>$row['is_old_version'],
							'account_name'=>$row['account_name'],
							'glusr_mobile'=>$row['glusr_mobile'],
							'glusr_mobile_key'=>$row['glusr_mobile_key'],
							'assign_rule'=>$row['assign_rule'],
							'assign_to'=>$row['assign_to'],
							'assign_start'=>$row['assign_start'],
							'assign_to_str'=>$assign_to_name
						);
				}
			}
		}
		
		return $return;
	}
    // Indiamart_setting_model
    // ===========================================================================

    // ===========================================================================
    // customer_model
    function CreateCustomer($data,$client_info=array())
    {   
        

        if($this->client_db->insert('customer',$data))
        {   
            $last_id=$this->client_db->insert_id();            
            return $last_id;
        }
        else
        {
          return false;
        }
    }

    function UpdateCustomer($data,$id,$client_info=array())
    {   
          

        $this->client_db->where('id',$id);
        if($this->client_db->update('customer',$data))
        {           
            //return true;
            return $tmp_log_id;
        }
        else
        {
            return false;
        }       

    } 
    function cust_get_decision($arg=array(),$client_info=array())
	{		

		$email=$arg['email'];
		$mobile=$arg['mobile'];
		$im_query_id=$arg['im_query_id'];
		if($im_query_id)
		{
			$sql_0="SELECT id FROM lead WHERE im_query_id='".$im_query_id."'";
			$query_0=$this->client_db->query($sql_0);
			if($query_0){
				if($query_0->num_rows()==0)
				{
					if($email!='' || $mobile!='')
					{
						if($email!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND email='".$email."'";
							$query=$this->client_db->query($sql);
							if($query){
								if($query->num_rows()==0)
								{			
									// return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
									if($mobile!='')
									{
										$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
										$query=$this->client_db->query($sql);	
										if($query){
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
										else{
											return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
										}
																					
									} 
									else {
										return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
									}
								}
								else if($query->num_rows()==1)
								{
									$row=$query->row();
									return array('msg'=>'one_customer_exist','customer_id'=>$row->id,'status'=>TRUE);	
								}
							}	
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}	
													
						}	

						if($mobile!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
							$query=$this->client_db->query($sql);
							if($query){
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
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}	
																		
						}		
					}
					else
					{
						return array('msg'=>'SENDEREMAIL and MOB both missing','customer_id'=>'','status'=>FALSE);
					}
				}
				else
				{
					$row_0=$query_0->row();
					return array('msg'=>'QUERY_ID already exist to the lead -'.$row_0->id,'customer_id'=>'','status'=>FALSE);
				}
			}
			else{
				return array('msg'=>'QUERY_ID missing','customer_id'=>'','status'=>FALSE);
			}
			
		}
		else
		{
			return array('msg'=>'QUERY_ID missing','customer_id'=>'','status'=>FALSE);
		}					
	}

    function cust_get_company_detail($id,$client_info=array())
	{		
		
		$sql="SELECT c.id,
			c.assigned_user_id,
			c.first_name,
			c.last_name,
			c.contact_person,
			c.designation,
			c.email,
			c.alt_email,
			c.mobile_country_code,
			c.mobile,
			c.alt_mobile_country_code,
			c.alt_mobile,
			c.landline_country_code,
			c.landline_std_code,
			c.landline_number,
			c.office_phone,
			c.website,
			c.company_name,
			c.address,
			c.city,
			c.state,
			c.country_id,
			c.zip,
			c.gst_number,
			c.create_date,
			c.short_description,
			c.source_id,
			c.modify_date,
			c.status,
			c.last_mail_sent,
			c.is_blacklist,
			ct.name AS city_name,
			st.name AS state_name,
			cnt.name AS country_name,
			source.name AS source_name
			FROM customer AS c		
			LEFT JOIN cities AS ct ON ct.id=c.city
			LEFT JOIN states AS st ON st.id=c.state
			LEFT JOIN countries AS cnt ON cnt.id=c.country_id
			LEFT JOIN source ON source.id=c.source_id 
			WHERE c.id='".$id."' AND c.status='1'";
		$result=$this->client_db->query($sql);
		//return $result->row_array();

		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

    public function cust_GetCustomerData($id,$client_info=array())
	{
		
		$this->client_db->select('customer.id,customer.assigned_user_id,customer.first_name,customer.last_name,customer.contact_person,customer.designation,customer.dob,customer.doa,customer.email,customer.alt_email,customer.mobile_country_code,customer.mobile,customer.mobile_whatsapp_status,customer.alt_mobile_country_code,customer.alt_mobile,customer.landline_country_code,customer.landline_std_code,customer.landline_number,customer.office_country_code,customer.office_std_code,customer.office_phone,customer.website,customer.company_name,customer.address,customer.city,customer.state,customer.country_id,customer.zip,customer.gst_number,customer.create_date,customer.short_description,customer.source_id,customer.business_type_id,customer.modify_date,customer.status,customer.last_mail_sent,customer.reference_name,countries.name as country_name,states.name as state_name,cities.name as city_name,source.name source_name,source.alias_name source_alias_name');
		$this->client_db->from('customer');
		$this->client_db->join('countries', 'countries.id = customer.country_id', 'left');
		$this->client_db->join('states', 'states.id = customer.state', 'left');
		$this->client_db->join('cities', 'cities.id = customer.city', 'left');
		$this->client_db->join('source', 'source.id = customer.source_id', 'left');
		$this->client_db->join('tbl_customer_business_type', 'tbl_customer_business_type.id = customer.business_type_id', 'left');
		$this->client_db->where('customer.id',$id);
		$this->client_db->where('customer.status','1');
		$result=$this->client_db->get();
		//return $result->row();

		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
    // customer_model
    // ===========================================================================

     // ===========================================================================
    // Lead_model
    function lead_CreateLead($data,$client_info=array())
	{			
		if($this->client_db->insert('lead',$data))
   		{			
           return $this->client_db->insert_id();
   		}
   		else
   		{
			// echo $last_query = $this->client_db->last_query();die('ok');
          	return false;
   		}
	}
    function lead_CreateLeadStageLog($data,$client_info=array())
	{
		
		if($this->client_db->insert('lead_stage_log',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}
    function lead_CreateLeadStatusLog($data,$client_info=array())
	{
		
		if($this->client_db->insert('lead_status_log',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}
    function lead_create_lead_assigned_user_log($data,$client_info=array())
	{
		
		if($this->client_db->insert('lead_assigned_user_log',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}
    public function lead_GetLeadData($id,$client_info=array())
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
		//return $result->row();

		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
    // Lead_model
    // ===========================================================================

    // ===========================================================================
    // History_model
    function history_CreateHistory($data,$client_info=array())
	{
        
		if($this->client_db->insert('lead_comment',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
    // History_model
    // ===========================================================================

    // ===========================================================================
    // User_model
    function user_get_employee_details($id,$client_info=array())
    {   
    	
        $sql="SELECT t1.id,
			t1.employee_type_id,
			t1.branch_id,
        	t1.department_id,
        	t1.manager_id,
        	t1.designation_id,
        	t1.functional_area_id,
        	t1.user_type,
        	t1.name,
        	t1.designation,
        	t1.email,
        	t1.mobile,
        	t1.company_name,
        	t1.address,
        	t1.city,
        	t1.state,
        	t1.country_id,
        	t1.website,
        	t1.company_industry_id,
        	t1.company_profile,
        	t1.date_of_birth,
        	t1.photo,
        	t1.personal_email,
        	t1.personal_mobile,
        	t1.gender,
        	t1.marital_status,
        	t1.marriage_anniversary,
        	t1.spouse_name,
        	t1.salary,
        	t1.salary_currency_code,
        	t1.pan,
        	t1.aadhar,
        	t1.joining_date,
        	t1.next_appraisal_date,
        	t1.sales_target_revenue,
        	t1.sales_target_revenue_type,
        	t1.sales_target_no_of_deal,
        	t1.target_setting,
        	t1.lms_url,
        	t1.create_date,
        	t1.modify_date,
        	t1.status,
        	t2.category_name AS dept_name,
        	t3.name AS manager_name,
        	t4.name AS designation_name,
        	t5.name AS functional_area_name,
        	t6.name AS country_name,
        	t7.name AS state_name,
        	t8.name AS city_name,
			t9.name AS employee_type,
			t10.name AS branch_name,
			if(t10.company_setting_id,'Main Branch','') AS cs_branch_name  FROM user AS t1
        LEFT JOIN category AS t2 ON t1.department_id=t2.id
        LEFT JOIN user AS t3 ON t1.manager_id=t3.id
        LEFT JOIN designation AS t4 ON t1.designation_id=t4.id
        LEFT JOIN functional_area AS t5 ON t1.functional_area_id=t5.id
        LEFT JOIN countries AS t6 ON t1.country_id=t6.id
        LEFT JOIN states AS t7 ON t1.state=t7.id
        LEFT JOIN cities AS t8 ON t1.city=t8.id 
		LEFT JOIN employee_type AS t9 ON t1.employee_type_id=t9.id
		LEFT JOIN company_branch AS t10 ON t1.branch_id=t10.id
        WHERE t1.id='".$id."'";        

        $query = $this->client_db->query($sql,false);        
        //return $last_query = $this->client_db->last_query();
        //return $query->result_array()[0];    
		
		if($query){
			return $query->result_array()[0];
		}
		else{
			return array();
		}
    }
    // // User_model
    // ===========================================================================

    // ===========================================================================
    // Setting_model
    public function setting_GetCompanyData($client_info=array())
	{	
		
		$this->client_db->select('
			company_setting.id,
			company_setting.logo,
			company_setting.brochure_file,
			company_setting.name,
			company_setting.address,
			company_setting.city_id,
			company_setting.state_id,
			company_setting.country_id,
			company_setting.pin,
			company_setting.about_company,
			company_setting.gst_number,
			company_setting.pan_number,
			company_setting.default_currency,
			company_setting.ceo_name,
			company_setting.contact_person,
			company_setting.email1,
			company_setting.email2,
			company_setting.mobile1,
			company_setting.mobile2,
			company_setting.phone1,
			company_setting.phone2,
			company_setting.website,
			company_setting.quotation_cover_letter_body_text,
			company_setting.quotation_terms_and_conditions,
			company_setting.quotation_cover_letter_footer_text,
			company_setting.quotation_bank_details1,
			company_setting.quotation_bank_details2,
			company_setting.bank_credit_to,
			company_setting.bank_name,
			company_setting.bank_acount_number,
			company_setting.bank_branch_name,
			company_setting.bank_branch_code,
			company_setting.bank_ifsc_code,
			company_setting.bank_swift_number,
			company_setting.bank_telex,
			company_setting.bank_address,
			company_setting.correspondent_bank_name,
			company_setting.correspondent_bank_swift_number,
			company_setting.correspondent_account_number,
			company_setting.indiamart_glusr_mobile,
			company_setting.indiamart_glusr_mobile_key,
			company_setting.indiamart_assign_to,
			company_setting.indiamart_assign_start,
			company_setting.is_system_generated_enquiryid_logic,
			company_setting.enquiryid_initial,
			company_setting.c2c_api_dial_url,
			company_setting.c2c_api_userid,
			company_setting.c2c_api_password,
			company_setting.c2c_api_client_name,
			company_setting.is_daily_report_send,
			company_setting.daily_report_tomail,
			company_setting.daily_report_mail_subject,
			company_setting.digital_signature,
			company_setting.authorized_signatory,
			company_setting.is_cronjobs_auto_regretted_on,
			company_setting.auto_regretted_day_interval,
			company_setting.is_session_expire_for_idle,
			company_setting.idle_time,
			company_setting.google_map_api_key,
			company_setting.updated_at,
			countries.name as country_name,
			states.name as state_name,
			cities.name as city_name,currency.name AS default_currency_name,currency.code AS default_currency_code');
		$this->client_db->from('company_setting');
		$this->client_db->join('countries', 'countries.id = company_setting.country_id','left');
		$this->client_db->join('states', 'states.id = company_setting.state_id', 'left');
		$this->client_db->join('cities', 'cities.id = company_setting.city_id', 'left');
		$this->client_db->join('currency', 'currency.id = company_setting.default_currency', 'left');
		$this->client_db->where('company_setting.id',1);
		$result=$this->client_db->get();
		//echo $last_query = $this->client_db->last_query();  die('ok');
		//return $result->row_array();

		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}
    // Setting_model
    // ===========================================================================
    
    // ===========================================================================
    // Email_forwarding_setting_model
    public function email_forwarding_setting_GetDetails($id,$client_info=array())
	{
		
		$sql="SELECT t1.id,
				t1.mail_name,
				t1.mail_keyword,
				t1.is_mail_send,
				t1.is_send_mail_to_client,
				t1.is_send_relationship_manager,
				t1.is_send_manager,
				t1.is_send_skip_manager				
				FROM email_forwarding_settings AS t1 WHERE id='".$id."'";
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
		
	}
    // Email_forwarding_setting_model
    // ===========================================================================

    // ===========================================================================
    // Sms_forwarding_setting_model
    public function sms_forwarding_setting_GetDetails($id,$client_info=array())
	{
		
		$sql="SELECT t1.id,
					t1.sms_name,
					t1.sms_keyword,
					t1.is_sms_send,
					t1.default_template_id,
					t1.is_send_sms_to_client,
					t1.send_sms_to_client_template_id,
					t1.is_send_relationship_manager,
					t1.send_relationship_manager_template_id,
					t1.is_send_manager,
					t1.send_manager_template_id,
					t1.is_send_skip_manager,
					t1.send_skip_manager_template_id				
				FROM sms_forwarding_settings AS t1 WHERE t1.id='".$id."' AND t1.is_active='Y'";
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		$return=array();
		//return $query->row_array();

		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}
    // Sms_forwarding_setting_model
    // ===========================================================================


    // ===========================================================================
    // get_lead_from_tradeindia()
    public function tradeindia_setting_GetCredentials($client_info=array())
	{			
		$sql="SELECT id,
				account_name,
				userid,
				profileid,
				ti_key,
				assign_rule,
				assign_to,
				assign_start FROM tradeindia_setting";
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result_array() AS $row)
				{
					$assign_to_arr=array();
					if($row['assign_to']){
						$assign_to_arr=unserialize($row['assign_to']);
					}
					$assign_to_atr=='';
					if(count($assign_to_arr)){
						$assign_to_atr=implode(",",$assign_to_arr);
					}
					
					if($assign_to_atr)
					{
						$sql2="SELECT GROUP_CONCAT(name) AS assign_to_str from user WHERE id IN ($assign_to_atr)";
						$query2 = $this->client_db->query($sql2,false);        
						$row2=$query2->row_array();
						$assign_to_name=$row2['assign_to_str'];
					}
					else
					{
						$assign_to_name='--';
					}
					
					$return[]=array(
							'id'=>$row['id'],
							'account_name'=>$row['account_name'],
							'userid'=>$row['userid'],
							'profileid'=>$row['profileid'],
							'ti_key'=>$row['ti_key'],
							'assign_rule'=>$row['assign_rule'],
							'assign_to'=>$row['assign_to'],
							'assign_start'=>$row['assign_start'],
							'assign_to_str'=>$assign_to_name
						);
				}
			}
		}
		
		return $return;
	}

    function tradeindia_truncate($client_info=array())
	{		
		if($this->client_db->truncate('tradeindia_tmp'))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}
    function tradeindia_insert($data,$client_info=array())
	{
		
		if($this->client_db->insert('tradeindia_tmp',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
    public function tradeindia_get_rows($s_id='',$client_info=array())
	{
		
		$subsql="";
		if($s_id)
		{
			$subsql .=" AND tradeindia_setting_id='".$s_id."'";
		}
		$sql="SELECT id,
			tradeindia_setting_id,
			source,
			receiver_name,
			generated_on,
			generated_date,
			generated_time,
			sender_mobile,
			sender_co,
			receiver_uid,
			inquiry_type,
			sender_country,
			ago_time,
			message,
			sender_state,
			subject,
			sender_city,
			product_source,
			view_status,
			rfi_id,
			month_slot,
			sender_name,
			sender_uid,
			sender,
			receiver_mobile,
			product_name,
			receiver_co,
			sender_email 
			FROM tradeindia_tmp WHERE 1=1 $subsql ORDER BY tradeindia_setting_id ASC,id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result_array();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
    function customer_get_decision_for_ti($arg=array(),$client_info=array())
	{
		
		$email=$arg['email'];
		$mobile=$arg['mobile'];
		$rfi_id=$arg['rfi_id'];
		if($rfi_id)
		{
			$sql_0="SELECT id FROM lead WHERE ti_rfi_id='".$rfi_id."'";
			$query_0=$this->client_db->query($sql_0);
			if($query_0){
				if($query_0->num_rows()==0)
				{
					if($email!='' || $mobile!='')
					{
						if($email!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND email='".$email."'";
							$query=$this->client_db->query($sql);	
							if($query){
								if($query->num_rows()==0)
								{			
									//return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
									if($mobile!='')
									{
										$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
										$query=$this->client_db->query($sql);	
										if($query){
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
										else{
											return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
										}
																				
									} else {
										return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
									}

								}
								else if($query->num_rows()==1)
								{
									$row=$query->row();
									return array('msg'=>'one_customer_exist','customer_id'=>$row->id,'status'=>TRUE);	
								}
							}	
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}
													
						}	

						if($mobile!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
							$query=$this->client_db->query($sql);	
							if($query){
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
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}
																	
						}		
					}
					else
					{
						return array('msg'=>'sender_email and sender_mobile both missing','customer_id'=>'','status'=>FALSE);
					}
				}
				else
				{
					$row_0=$query_0->row();
					return array('msg'=>'sender_uid already exist to the lead -'.$row_0->id,'customer_id'=>'','status'=>FALSE);
				}
			}
			else{
				return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
			}
			
		}
		else
		{
			return array('msg'=>'sender_uid missing','customer_id'=>'','status'=>FALSE);
		}					
	}

    public function countries_get_country_by_name($name,$client_info=array())
	{		
		$sql="SELECT id,sortname,name,phonecode FROM countries WHERE replace(LOWER(name),' ', '')='".str_replace(' ', '', strtolower($name))."'";
		$query=$this->client_db->query($sql);	
		if($query->num_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
    public function tradeindia_setting_get_keyword_rule5_wise_assigned_user_id($arr,$client_info=array())
	{
		
		$assign_rule=$arr['assign_rule'];
		$tradeindia_setting_id=$arr['tradeindia_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM tradeindia_assign_rule_details WHERE ti_setting_id='".$tradeindia_setting_id."' AND assign_rule_id='".$assign_rule."'";
		$query = $this->client_db->query($sql,false);
		$return=array();
		if($query->num_rows())
		{
			foreach($query->result_array() AS $row)
			{
				$id=$row['id'];
				$find_to=unserialize($row['find_to']);
				$assign_to=unserialize($row['assign_to']);
				$assign_start=$row['assign_start'];	
				$assign_end=(count($assign_to)-1);
				if($find_to!='other')
				{         
					if(count($find_to))
					{
						for($i=0;$i<count($find_to);$i++)
						{ 
							if(strpos($search_keyword, str_replace(' ', '', strtolower($find_to[$i]))) !== false)
							{					
								$assign_start++;
								if($assign_start>$assign_end)
								{
									$assign_start=0;
								}
								break 2;
							}        
						} 
					}
				}
				else if($find_to=='other')
				{
					$assign_start++;
					if($assign_start>$assign_end)
					{
						$assign_start=0;
					}  
					break;					   
				}
			}
			$post_data=array('assign_start'=>$assign_start);
			$this->client_db->where('id',$id);
			$this->client_db->update('tradeindia_assign_rule_details',$post_data);	
			return $assign_to[$assign_start];
		}
	}

    public function tradeindia_setting_get_rule_wise_assigned_user_id($arr,$client_info=array())
	{
		

		$assign_rule=$arr['assign_rule'];
		$tradeindia_setting_id=$arr['tradeindia_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM tradeindia_assign_rule_details WHERE ti_setting_id='".$tradeindia_setting_id."' AND assign_rule_id='".$assign_rule."'";

		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query->num_rows())
		{

			foreach($query->result_array() AS $row)
			{
				$id=$row['id'];
				$find_to=unserialize($row['find_to']);
				$assign_to=unserialize($row['assign_to']);
				$assign_start=$row['assign_start'];	
				$assign_end=(count($assign_to)-1);

				if(in_array($search_keyword, $find_to))
				{
					//$tmp_assigned_user_id=$assign_to[$assign_start];
					$assign_start++;
					if($assign_start>$assign_end)
					{
						$assign_start=0;
					}

					// $post_data=array('assign_start'=>$assign_start);
					// $this->client_db->where('id',$id);
					// $this->client_db->update('indiamart_assign_rule_details',$post_data);

					// return $tmp_assigned_user_id;
					break;
				}
				else
				{
					if($find_to=='other')
					{
						//$tmp_assigned_user_id=$assign_to[$assign_start];
						$assign_start++;
						if($assign_start>$assign_end)
						{
							$assign_start=0;
						}

						// $post_data=array('assign_start'=>$assign_start);
						// $this->client_db->where('id',$id);
						// $this->client_db->update('tradeindia_assign_rule_details',$post_data);

						// return $tmp_assigned_user_id;
						break;
					}
				}
			}

			$post_data=array('assign_start'=>$assign_start);
			// echo $id.':'.$assign_start.'<br>';
			// echo $assign_to[$assign_start].'<br>';
			$this->client_db->where('id',$id);
			$this->client_db->update('tradeindia_assign_rule_details',$post_data);	
			// echo $last_query = $this->client_db->last_query();die();		
			return $assign_to[$assign_start];
		}		
	}

    public function tradeindia_setting_EditCredentials($data,$id,$client_info=array())
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('tradeindia_setting',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

    public function tradeindia_update($data,$id,$client_info=array())
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('tradeindia_tmp',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}
    // get_lead_from_tradeindia()
    // ===========================================================================

    // ===========================================================================
    // get_lead_from_aajjo()

    public function aajjo_setting_GetCredentials($client_info=array())
	{	
		
		$sql="SELECT id,
				account_name,
				username,
				aj_key,
				assign_rule,
				assign_to,
				assign_start FROM aajjo_setting";
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result_array() AS $row)
				{
					$assign_to_arr=unserialize($row['assign_to']);
					$assign_to_atr=implode(",",$assign_to_arr);
					if($assign_to_atr)
					{
						$sql2="SELECT GROUP_CONCAT(name) AS assign_to_str from user WHERE id IN ($assign_to_atr)";
						$query2 = $this->client_db->query($sql2,false);        
						$row2=$query2->row_array();
						$assign_to_name=$row2['assign_to_str'];
					}
					else
					{
						$assign_to_name='--';
					}
					
					$return[]=array(
							'id'=>$row['id'],
							'account_name'=>$row['account_name'],
							'username'=>$row['username'],
							'aj_key'=>$row['aj_key'],
							'assign_rule'=>$row['assign_rule'],
							'assign_to'=>$row['assign_to'],
							'assign_start'=>$row['assign_start'],
							'assign_to_str'=>$assign_to_name
						);
				}
			}
		}
		
		return $return;
	}

    function aajjo_truncate($client_info=array())
	{
		
		if($this->client_db->truncate('aajjo_tmp'))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}

    public function aajjo_is_record_already_exist($arr,$client_info=array())
	{	
		
		$sql="SELECT id,is_deleted FROM aajjo_tmp WHERE created_date='".$arr['created_date']."'";
		$query = $this->client_db->query($sql,false);
		$return=array();
		if($query->num_rows()>0)
		{			   
			$row=$query->row();
			return array('exist'=>'Y','id'=>$row->id,'is_deleted'=>$row->is_deleted);
		}
		else
		{
			return array('exist'=>'N','id'=>'','is_deleted'=>'');
		}
	}
    function aajjo_insert($data,$client_info=array())
	{
		
		if($this->client_db->insert('aajjo_tmp',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
    public function aajjo_update($data,$id,$client_info=array())
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('aajjo_tmp',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

    public function aajjo_get_rows($s_id='',$client_info=array())
	{
		
		$subsql="";
		if($s_id)
		{
			$subsql .=" AND aajjo_setting_id='".$s_id."'";
		}
		$sql="SELECT id,
			aajjo_setting_id,
			contact_person,
			product,
			email_id,
			phone_number,
			city,
			state_name,
			lead_address,
			country_name,
			lead_details,
			created_date,
			consumed_date,
			lead_type,
			created_at,
			msg,
			is_deleted 
			FROM aajjo_tmp WHERE is_deleted='N' $subsql ORDER BY aajjo_setting_id ASC,id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result_array();

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

    function cust_get_decision_for_aajjo($arg=array(),$client_info=array())
	{
		$aj_created_date=$arg['aj_created_date'];
		$email=$arg['email'];
		$mobile=$arg['mobile'];	
        if($aj_created_date)
		{
            $sql_0="SELECT id FROM lead WHERE aj_created_date='".$aj_created_date."'";
			$query_0=$this->client_db->query($sql_0);
			if($query_0){
				if($query_0->num_rows()==0)
				{
					if($email!='' || $mobile!='')
					{
						if($email!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND email='".$email."'";
							$query=$this->client_db->query($sql);	
							if($query){
								if($query->num_rows()==0)
								{			
									// return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
									if($mobile!='')
									{
										$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
										$query=$this->client_db->query($sql);
										if($query){
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
										else{
											return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
										}	
																					
									} 
									else {
										return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
									}
								}
								else if($query->num_rows()==1)
								{
									$row=$query->row();
									return array('msg'=>'one_customer_exist','customer_id'=>$row->id,'status'=>TRUE);	
								}
							}	
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}
													
						}	

						if($mobile!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
							$query=$this->client_db->query($sql);		
							if($query){
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
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}
																		
						}		
					}
					else
					{
						return array('msg'=>'email and mobile both missing','customer_id'=>'','status'=>FALSE);
					}
				}
				else
				{
					$row_0=$query_0->row();
					return array('msg'=>'created_date already exist to the lead -'.$row_0->id,'customer_id'=>'','status'=>FALSE);
				}
			}
			else
			{
				return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
			}	
			
        }
        else
        {
            return array('msg'=>'created_date missing','customer_id'=>'','status'=>FALSE);
        }							
	}
    public function aajjo_setting_get_keyword_rule5_wise_assigned_user_id($arr,$client_info=array())
	{
		
		$assign_rule=$arr['assign_rule'];
		$aajjo_setting_id=$arr['aajjo_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM aajjo_assign_rule_details WHERE aj_setting_id='".$aajjo_setting_id."' AND assign_rule_id='".$assign_rule."'";
		$query = $this->client_db->query($sql,false);
		$return=array();
		if($query->num_rows())
		{
			foreach($query->result_array() AS $row)
			{
				$id=$row['id'];
				$find_to=unserialize($row['find_to']);
				$assign_to=unserialize($row['assign_to']);
				$assign_start=$row['assign_start'];	
				$assign_end=(count($assign_to)-1);
				if($find_to!='other')
				{         
					if(count($find_to))
					{
						for($i=0;$i<count($find_to);$i++)
						{
							if(strpos($search_keyword, str_replace(' ', '', strtolower($find_to[$i]))) !== false)
							{					
								$assign_start++;
								if($assign_start>$assign_end)
								{
									$assign_start=0;
								}
								break 2;
							}        
						} 
					}
				}
				else if($find_to=='other')
				{
					$assign_start++;
					if($assign_start>$assign_end)
					{
						$assign_start=0;
					}  
					break;					   
				}
			}
			$post_data=array('assign_start'=>$assign_start);
			$this->client_db->where('id',$id);
			$this->client_db->update('aajjo_assign_rule_details',$post_data);	
			return $assign_to[$assign_start];
		}
	}

    public function aajjo_setting_get_rule_wise_assigned_user_id($arr,$client_info=array())
	{
		

		$assign_rule=$arr['assign_rule'];
		$aajjo_setting_id=$arr['aajjo_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM aajjo_assign_rule_details WHERE aj_setting_id='".$aajjo_setting_id."' AND assign_rule_id='".$assign_rule."'";

		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query->num_rows())
		{

			foreach($query->result_array() AS $row)
			{
				$id=$row['id'];
				$find_to=unserialize($row['find_to']);
				$assign_to=unserialize($row['assign_to']);
				$assign_start=$row['assign_start'];	
				$assign_end=(count($assign_to)-1);

				if(in_array($search_keyword, $find_to))
				{
					//$tmp_assigned_user_id=$assign_to[$assign_start];
					$assign_start++;
					if($assign_start>$assign_end)
					{
						$assign_start=0;
					}

					// $post_data=array('assign_start'=>$assign_start);
					// $this->client_db->where('id',$id);
					// $this->client_db->update('indiamart_assign_rule_details',$post_data);

					// return $tmp_assigned_user_id;
					break;
				}
				else
				{
					if($find_to=='other')
					{
						//$tmp_assigned_user_id=$assign_to[$assign_start];
						$assign_start++;
						if($assign_start>$assign_end)
						{
							$assign_start=0;
						}

						// $post_data=array('assign_start'=>$assign_start);
						// $this->client_db->where('id',$id);
						// $this->client_db->update('tradeindia_assign_rule_details',$post_data);

						// return $tmp_assigned_user_id;
						break;
					}
				}
			}

			$post_data=array('assign_start'=>$assign_start);
			// echo $id.':'.$assign_start.'<br>';
			// echo $assign_to[$assign_start].'<br>';
			$this->client_db->where('id',$id);
			$this->client_db->update('aajjo_assign_rule_details',$post_data);	
			// echo $last_query = $this->client_db->last_query();die();		
			return $assign_to[$assign_start];
		}		
	}
    public function aajjo_setting_EditCredentials($data,$id,$client_info=array())
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('aajjo_setting',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}
    // get_lead_from_aajjo()
    // ===========================================================================

    // ===========================================================================
    // get_lead_from_justdial()
    public function justdial_setting_GetCredentials($client_info=array())
	{
		$sql="SELECT id,
			account_name,
			assign_rule,
			assign_to,
			assign_start FROM justdial_setting LIMIT 1";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result_array() AS $row)
				{
					$assign_to_arr=array();
					if($row['assign_to']){
						$assign_to_arr=unserialize($row['assign_to']);
					}
					$assign_to_atr='';
					if(count($assign_to_arr)){
						$assign_to_atr=implode(",",$assign_to_arr);
					}
					
					if($assign_to_atr)
					{
						$sql2="SELECT GROUP_CONCAT(name) AS assign_to_str from user WHERE id IN ($assign_to_atr)";
						$query2 = $this->client_db->query($sql2,false);
						$row2=$query2->row_array();
						$assign_to_name=$row2['assign_to_str'];
					}
					else
					{
						$assign_to_name='--';
					}
					
					$return=array(
							'id'=>$row['id'],
							'account_name'=>$row['account_name'],
							'assign_rule'=>$row['assign_rule'],
							'assign_to'=>$row['assign_to'],
							'assign_start'=>$row['assign_start'],
							'assign_to_str'=>$assign_to_name
						);
				}
			}
		}
		
		return $return;
	}
    public function justdial_get_rows($client_info=array())
	{		

		$subsql="";		
		$sql="SELECT id,
				leadid,
				leadtype,
				prefix,
				name,
				mobile,
				phone,
				email,
				enq_date,
				category,
				city,
				area,
				brancharea,
				dncmobile,
				dncphone,
				company,
				pincode,
				time,
				branchpin,
				parentid,
				created_at 
				FROM justdial_tmp 
				WHERE 1=1 $subsql 
				ORDER BY id ASC";
		$result=$this->client_db->query($sql);
		//return $result->result_array();

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

    function cust_get_decision_jd($arg=array(),$client_info=array())
	{
		

		$email=$arg['email'];
		$mobile=$arg['mobile'];		
		if($email!='' || $mobile!='')
		{
			if($email!='')
			{
				$sql="SELECT id FROM customer WHERE status='1' AND email='".$email."'";
				$query=$this->client_db->query($sql);	
				if($query){
					if($query->num_rows()==0)
					{			
						// return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
						if($mobile!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
							$query=$this->client_db->query($sql);		
							if($query){
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
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}
																	
						} 
						else {
							return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
						}
					}
					else if($query->num_rows()==1)
					{
						$row=$query->row();
						return array('msg'=>'one_customer_exist','customer_id'=>$row->id,'status'=>TRUE);	
					}
				}	
				else{
					return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
				}
										
			}	

			if($mobile!='')
			{
				$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
				$query=$this->client_db->query($sql);
				if($query){
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
				else{
					return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
				}	
															
			}		
		}
		else
		{
			return array('msg'=>'email and mobile both missing','customer_id'=>'','status'=>FALSE);
		}
		
						
	}

    public function justdial_update($data,$id,$client_info=array())
	{
		
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('justdial_tmp',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}
    public function justdial_setting_get_keyword_rule5_wise_assigned_user_id($arr,$client_info=array())
	{
		
		$assign_rule=$arr['assign_rule'];
		$justdial_setting_id=$arr['justdial_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM justdial_assign_rule_details WHERE jd_setting_id='".$justdial_setting_id."' AND assign_rule_id='".$assign_rule."'";
		$query = $this->client_db->query($sql,false);
		$return=array();
		if($query->num_rows())
		{
			foreach($query->result_array() AS $row)
			{
				$id=$row['id'];
				$find_to=unserialize($row['find_to']);
				$assign_to=unserialize($row['assign_to']);
				$assign_start=$row['assign_start'];	
				$assign_end=(count($assign_to)-1);
				if($find_to!='other')
				{         
					if(count($find_to))
					{
						for($i=0;$i<count($find_to);$i++)
						{        
							if(strpos($search_keyword, $find_to[$i]) !== false)
							{					
								$assign_start++;
								if($assign_start>$assign_end)
								{
									$assign_start=0;
								}
								break 2;
							}        
						} 
					}
				}
				else if($find_to=='other')
				{
					$assign_start++;
					if($assign_start>$assign_end)
					{
						$assign_start=0;
					}  
					break;					   
				}
			}
			$post_data=array('assign_start'=>$assign_start);
			$this->client_db->where('id',$id);
			$this->client_db->update('justdial_assign_rule_details',$post_data);	
			return $assign_to[$assign_start];
		}
	}
    public function justdial_setting_get_rule_wise_assigned_user_id($arr,$client_info=array())
	{
		

		$assign_rule=$arr['assign_rule'];
		$justdial_setting_id=$arr['justdial_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM justdial_assign_rule_details WHERE jd_setting_id='".$justdial_setting_id."' AND assign_rule_id='".$assign_rule."'";

		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query->num_rows())
		{

			foreach($query->result_array() AS $row)
			{
				$id=$row['id'];
				$find_to=unserialize($row['find_to']);
				$assign_to=unserialize($row['assign_to']);
				$assign_start=$row['assign_start'];	
				$assign_end=(count($assign_to)-1);

				if(in_array($search_keyword, $find_to))
				{
					//$tmp_assigned_user_id=$assign_to[$assign_start];
					$assign_start++;
					if($assign_start>$assign_end)
					{
						$assign_start=0;
					}

					// $post_data=array('assign_start'=>$assign_start);
					// $this->client_db->where('id',$id);
					// $this->client_db->update('indiamart_assign_rule_details',$post_data);

					// return $tmp_assigned_user_id;
					break;
				}
				else
				{
					if($find_to=='other')
					{
						//$tmp_assigned_user_id=$assign_to[$assign_start];
						$assign_start++;
						if($assign_start>$assign_end)
						{
							$assign_start=0;
						}

						// $post_data=array('assign_start'=>$assign_start);
						// $this->client_db->where('id',$id);
						// $this->client_db->update('tradeindia_assign_rule_details',$post_data);

						// return $tmp_assigned_user_id;
						break;
					}
				}
			}

			$post_data=array('assign_start'=>$assign_start);
			// echo $id.':'.$assign_start.'<br>';
			// echo $assign_to[$assign_start].'<br>';
			$this->client_db->where('id',$id);
			$this->client_db->update('justdial_assign_rule_details',$post_data);	
			// echo $last_query = $this->client_db->last_query();die();		
			return $assign_to[$assign_start];
		}
	}

    public function justdial_setting_EditJustdialCredentials($data,$id,$client_info=array())
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('justdial_setting',$data))
		{			
			//return true;		
		}
		else
		{
			//return false;
		}	
	}
    public function justdial_deleteTmp($id,$client_info=array())
	{
		

		$this->client_db->where('id', $id);
    	$this->client_db->delete('justdial_tmp');
	}
    // get_lead_from_justdial()
    // ===========================================================================

	public function get_manager_and_skip_manager_email_arr($user_id='',$client_info=array())
	{
		$manager_email='';
		$skip_manager_email='';
		$get_immidiate_manager_id=0;
		$get_manager_of_immidiate_manager_id=0;

		if($user_id){
			$sql="SELECT manager_id FROM user WHERE id='".$user_id."'"; 
			$query = $this->client_db->query($sql,false); 
			if($query){
				if($query->num_rows()>0){
					$row=$query->row();   
					$get_immidiate_manager_id=$row->manager_id;
				}			
			}
			if($get_immidiate_manager_id>0)
			{
				$sql2="SELECT manager_id FROM user WHERE id='".$get_immidiate_manager_id."'"; 
				$query2 = $this->client_db->query($sql2,false); 
				if($query2){
					if($query2->num_rows()>0){
						$row2=$query2->row();   
						$get_manager_of_immidiate_manager_id=$row2->manager_id;
					}			
				}
			}		
					
			if($get_immidiate_manager_id>0)
			{
				$sql_e="SELECT email FROM user WHERE id='".$get_immidiate_manager_id."'"; 
				$query_e=$this->client_db->query($sql_e,false);
				if($query_e){
					if($query_e->num_rows()>0){
						$row_e=$query_e->row();   
						$manager_email=$row_e->email;
					}			
				}

			}		
			if($get_manager_of_immidiate_manager_id>0)
			{
				$sql_e2="SELECT email FROM user WHERE id='".$get_manager_of_immidiate_manager_id."'"; 
				$query_e2=$this->client_db->query($sql_e2,false);
				if($query_e2){
					if($query_e2->num_rows()>0){
						$row_e2=$query_e2->row();   
						$skip_manager_email=$row_e2->email;
					}			
				}
			}
		}		
		$return=array(
				'manager_email'=>$manager_email,
				'skip_manager_email'=>$skip_manager_email
				);
		return $return;	
	}

	public function get_manager_and_skip_manager_mobile_arr($user_id='',$client_info=array())
	{
		$manager_email='';
		$skip_manager_email='';
		$get_immidiate_manager_id=0;
		$get_manager_of_immidiate_manager_id=0;
		if($user_id){
			$sql="SELECT manager_id FROM user WHERE id='".$user_id."'"; 
			$query = $this->client_db->query($sql,false); 
			if($query){
				if($query->num_rows()>0){
					$row=$query->row();   
					$get_immidiate_manager_id=$row->manager_id;
				}			
			}
			if($get_immidiate_manager_id>0)
			{
				$sql2="SELECT manager_id FROM user WHERE id='".$get_immidiate_manager_id."'"; 
				$query2 = $this->client_db->query($sql2,false); 
				if($query2){
					if($query2->num_rows()>0){
						$row2=$query2->row();   
						$get_manager_of_immidiate_manager_id=$row2->manager_id;
					}			
				}
			}		
					
			if($get_immidiate_manager_id>0)
			{			
				$sql_m="SELECT mobile FROM user WHERE id='".$get_immidiate_manager_id."'"; 
				$query_m=$this->client_db->query($sql_m,false);
				if($query_m){
					if($query_m->num_rows()>0){
						$row_m=$query_m->row();   
						$manager_email=$row_m->mobile;
					}			
				}			
			}		
			if($get_manager_of_immidiate_manager_id>0)
			{
				$sql_m2="SELECT mobile FROM user WHERE id='".$get_manager_of_immidiate_manager_id."'"; 
				$query_m2=$this->client_db->query($sql_m2,false);
				if($query_m2){
					if($query_m2->num_rows()>0){
						$row_m2=$query_m2->row();   
						$skip_manager_email=$row_m2->mobile;
					}			
				}
			}
		}
		
		$return=array(
				'manager_mobile'=>$manager_email,
				'skip_manager_mobile'=>$skip_manager_email
				);
		return $return;	
	}

	public function get_company_name_initials($client_info=array())
	{
		$company_name_tmp = "";				
		$this->client_db->select('name,is_system_generated_enquiryid_logic,enquiryid_initial');
		$this->client_db->from('company_setting');		
		$this->client_db->where('id',1);
		$result=$this->client_db->get();		
		if($result){
			$company_data=$result->row_array();
			if($company_data['is_system_generated_enquiryid_logic']=='N'){ 
				$company_name_tmp = $company_data['enquiryid_initial'];
			}	
			if($company_name_tmp=='')
			{
				$words = explode(" ", $company_data['name']);				
				foreach ($words as $w) {
					$company_name_tmp .= strtoupper($w[0]);
				}
			}
		}		
		return $company_name_tmp;       	
	}

	public function rander_company_address_cronjobs()
	{		
		$address_str='';
		$this->client_db->select('
		company_setting.address,
		company_setting.pin,
		company_setting.email1,
		company_setting.email2,
		company_setting.mobile1,
		company_setting.mobile2,
		company_setting.phone1,
		company_setting.phone2,
		company_setting.website,
		countries.name as country_name,
		states.name as state_name,
		cities.name as city_name');
		$this->client_db->from('company_setting');
		$this->client_db->join('countries', 'countries.id = company_setting.country_id','left');
		$this->client_db->join('states', 'states.id = company_setting.state_id', 'left');
		$this->client_db->join('cities', 'cities.id = company_setting.city_id', 'left');			
		$this->client_db->where('company_setting.id',1);
		$result=$this->client_db->get();			
		if($result){
			$company=$result->row_array();			
			$address_str .=$company['address'];			
			if(trim($company['address']) && trim($company['city_name'])){$address_str .=', ';}
			if(trim($company['city_name'])){$address_str .= $company['city_name'];}
			if(trim($company['city_name']) && trim($company['state_name'])){$address_str .= ', ';}
			if(trim($company['state_name'])){$address_str .=$company['state_name'].' - '.$company['pin'];}
			if(trim($company['state_name']) && trim($company['country_name'])){$address_str .= ', ';}
			if(trim($company['country_name'])){$address_str .=$company['country_name'];}
			$address_str .='<br>';		
			$address_str .=($company['phone1'])?' Phone: '.$company['phone1']:'';
			$address_str .=($company['phone2'])?'/ '.$company['phone2']:'';
			$address_str .=($company['email1'])?' Email: '.$company['email1']:'';
			$address_str .=($company['website'])?' Website: '.$company['website']:'';
		}						
		return $address_str;		
	}

	public function get_user_info($user_id='')
	{
		$return_arr=array();
		$sql="SELECT name,email,mobile FROM user WHERE id='".$user_id."'"; 
    	$query = $this->client_db->query($sql,false); 
		if($query){
			if($query->num_rows()>0){
				$return_arr=$query->row_array();
			}			
		}		
		return $return_arr;       	
	}

	public function GetSmtpData()
	{
		
		$sql="SELECT id,smtp_type,host,port,username,password 
		FROM smtp_settings WHERE is_active='Y' AND is_default='N' LIMIT 1";
		$result=$this->client_db->query($sql);		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}
	public function GetDefaultSmtpData()
	{		
		
		$sql="SELECT id,smtp_type,username,password 
		FROM smtp_settings WHERE id='1'";
		$result=$this->client_db->query($sql);		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	function cronjobs_mail_fire_add($data)
    {         
        if($this->client_db->insert('tmp_cronjobs_mail_fire',$data))
        {   
            // $last_id=$this->client_db->insert_id();            
            return true;
        }
        else
        {
          return false;
        }
    }

	public function cronjobs_mail_fire_rows($mail_for='')
	{
		$subsql="";
		if($mail_for)
		{
			$subsql .=" AND mail_for='".$mail_for."'";
		}
		$sql="SELECT id,
				mail_for,
				from_mail,
				from_name,
				to_mail,
				cc_mail,
				subject,
				message,
				attach 
				FROM tmp_cronjobs_mail_fire WHERE 1=1 $subsql ORDER BY id ASC";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function cronjobs_mail_fire_truncate($mail_for='')
	{
		if($mail_for){
			$this->client_db->where('mail_for', $mail_for);
			$this->client_db->delete('tmp_cronjobs_mail_fire');
			return true;
		}
		else{
			if($this->client_db->truncate('tmp_cronjobs_mail_fire'))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	function cronjobs_mail_fire_delete($id='')
	{		
		if($id){
			$this->client_db->where('id', $id);
			$this->client_db->delete('tmp_cronjobs_mail_fire');
			if(!$this->client_db->affected_rows()){
				// $result = 'Error! ID ['.$id.'] not found';
				return false;
			}else{
				// $result = 'Success';
				return true;
			}			
		}	
		else{
			return false;
		}	
	}

	public function get_mail_fire_rows($limit)
	{
		$subsql="";
		// if($mail_for)
		// {
		// 	$subsql .=" AND mail_for='".$mail_for."'";
		// }
		$sql="SELECT id,
				mail_for,
				from_mail,
				from_name,
				to_mail,
				cc_mail,
				subject,
				message,
				attach 
				FROM tmp_cronjobs_mail_fire WHERE to_mail!='' $subsql ORDER BY id ASC LIMIT 0,$limit";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function delete_mail_junK_data()
	{
		$sql_delete="DELETE FROM tmp_cronjobs_mail_fire WHERE to_mail=''";
		$this->client_db->query($sql_delete);
	}

	function capture_nimbus_data_in_tmp_table($data)
    { 
        if($this->client_db->insert('nimbus_call_tmp',$data))
        {   
            $last_id=$this->client_db->insert_id();            
            return $last_id;
        }
        else
        {
          return false;
        }
    }

	public function GetUserName($id='')
	{		
		if($id){
			$sql="SELECT name FROM user WHERE id='".$id."'";
			$result=$this->client_db->query($sql);		
			if($result){
				return $result->row()->name;
			}
			else{
				return '';
			}
		}
		else{
			return '';
		}		
	}

	public function get_facebook_credentials()
	{
		$sql="SELECT 
		t1.id,
		t1.fb_page_id,
		t1.fb_page_access_token,
		t1.fb_form_id,
		t1.fb_long_lived_access_token,
		GROUP_CONCAT(t2.fb_field_name) AS fb_field_name_str,
		GROUP_CONCAT(t2.system_field_name_keyword) AS system_field_name_keyword_str		
		FROM fb_form_wise_lead_field_set AS t1  
		INNER JOIN fb_form_wise_lead_field_set_details AS t2 ON t1.id=t2.form_wise_lead_field_set_id  
		WHERE t1.is_default='Y' GROUP BY t2.form_wise_lead_field_set_id";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows()){
				$return=$query->row_array();
			}
		}		
		return $return;
	}
	public function addFacebookTmp($data)
	{
		if($this->client_db->insert('fb_lead_tmp',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function addFacebookFieldTmp($data)
	{
		if($this->client_db->insert('fb_lead_field_tmp',$data))
   		{
			// echo $this->client_db->last_query(); die();
           return true;
   		}
   		else
   		{
			// echo $this->client_db->last_query(); die();
          return false;
   		}
	}
	function fb_truncate()
	{		
		if($this->client_db->truncate('fb_lead_tmp')){
			if($this->client_db->truncate('fb_lead_field_tmp')){
				return true;
			}
			else{
				return false;
			}
   		}
   		else{
          return false;
   		}
	}

	public function fb_leads_tmp()
	{
		$sql="SELECT 
		t1.id,
		t1.fb_id,
		t1.ad_id,
		t1.ad_name,
		t1.adset_id,
		t1.adset_name,
		t1.campaign_id,
		t1.campaign_name,
		t1.created_time,
		t1.fb_form_id,
		t1.fb_is_organic,
		t1.fb_platform,
		GROUP_CONCAT(t2.fb_field_name) AS fb_field_name_str,
		GROUP_CONCAT(t2.fb_field_value SEPARATOR '~~~') AS fb_field_value_str 
		FROM fb_lead_tmp AS t1 INNER JOIN fb_lead_field_tmp AS t2 ON t1.id=t2.fb_lead_tmp_id GROUP BY t2.fb_lead_tmp_id ORDER BY DATE(t1.created_time)";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows()){
				$return=$query->result_array();
			}
		}		
		return $return;
	}

	function cust_get_decision_fb($arg=array())
	{		

		$email=$arg['email'];
		$mobile=$arg['mobile'];
		$fb_lead_id=$arg['fb_lead_id'];
		if($fb_lead_id)
		{
			$sql_0="SELECT id FROM lead WHERE fb_lead_id='".$fb_lead_id."'";
			$query_0=$this->client_db->query($sql_0);
			if($query_0){
				if($query_0->num_rows()==0)
				{
					if($email!='' || $mobile!='')
					{
						if($email!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND email='".$email."'";
							$query=$this->client_db->query($sql);
							if($query){
								if($query->num_rows()==0)
								{			
									// return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
									if($mobile!='')
									{
										$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
										$query=$this->client_db->query($sql);	
										if($query){
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
										else{
											return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
										}
																					
									} 
									else {
										return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
									}
								}
								else if($query->num_rows()==1)
								{
									$row=$query->row();
									return array('msg'=>'one_customer_exist','customer_id'=>$row->id,'status'=>TRUE);	
								}
							}	
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}	
													
						}	

						if($mobile!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
							$query=$this->client_db->query($sql);
							if($query){
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
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}	
																		
						}		
					}
					else
					{
						return array('msg'=>'email and mobile both missing','customer_id'=>'','status'=>FALSE);
					}
				}
				else
				{
					$row_0=$query_0->row();
					return array('msg'=>'fb_lead_id already exist to the lead -'.$row_0->id,'customer_id'=>'','status'=>FALSE);
				}
			}
			else{
				return array('msg'=>'fb_lead_id missing','customer_id'=>'','status'=>FALSE);
			}
			
		}
		else
		{
			return array('msg'=>'fb_lead_id missing','customer_id'=>'','status'=>FALSE);
		}					
	}

	public function fb_lead_tmp_update($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('fb_lead_tmp',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	public function delete_lead_tmp_by_user($user_id)
	{
		if($id){
			$this->client_db->where('user_id', $user_id);
			$this->client_db->delete('fb_lead_tmp');

			$this->client_db->where('user_id', $user_id);
			$this->client_db->delete('fb_lead_field_tmp');
			return true;
		}
		else{
			return false;
		}		
	}

	// ===========================================================================
    // EXPORTERINDIA
    public function exporterindia_setting_GetCredentials($client_info=array())
	{			
		$sql="SELECT id,
				account_name,
				userid,
				ti_key,
				assign_rule,
				assign_to,
				assign_start FROM exporterindia_setting";
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result_array() AS $row)
				{
					$assign_to_arr=array();
					if($row['assign_to']){
						$assign_to_arr=unserialize($row['assign_to']);
					}
					$assign_to_atr=='';
					if(count($assign_to_arr)){
						$assign_to_atr=implode(",",$assign_to_arr);
					}
					
					if($assign_to_atr)
					{
						$sql2="SELECT GROUP_CONCAT(name) AS assign_to_str from user WHERE id IN ($assign_to_atr)";
						$query2 = $this->client_db->query($sql2,false);        
						$row2=$query2->row_array();
						$assign_to_name=$row2['assign_to_str'];
					}
					else
					{
						$assign_to_name='--';
					}
					
					$return[]=array(
							'id'=>$row['id'],
							'account_name'=>$row['account_name'],
							'userid'=>$row['userid'],
							'ti_key'=>$row['ti_key'],
							'assign_rule'=>$row['assign_rule'],
							'assign_to'=>$row['assign_to'],
							'assign_start'=>$row['assign_start'],
							'assign_to_str'=>$assign_to_name
						);
				}
			}
		}
		
		return $return;
	}
	function exporterindia_truncate($client_info=array())
	{		
		if($this->client_db->truncate('exporterindia_tmp'))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}
	function exporterindia_insert($data,$client_info=array())
	{
		
		if($this->client_db->insert('exporterindia_tmp',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	public function exporterindia_get_rows($s_id='',$client_info=array())
	{
		
		$subsql="";
		if($s_id)
		{
			$subsql .=" AND exporterindia_setting_id='".$s_id."'";
		}
		$sql="SELECT id,
			exporterindia_setting_id,
			inq_id,
			enq_date,
			subject,
			detail_req,
			email,
			name,
			country,
			state,
			city,
			mobile,
			msg 
			FROM exporterindia_tmp WHERE 1=1 $subsql ORDER BY exporterindia_setting_id ASC,id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result_array();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	function customer_get_decision_for_ei($arg=array(),$client_info=array())
	{
		
		$email=$arg['email'];
		$mobile=$arg['mobile'];
		$inq_id=$arg['inq_id'];
		
		if($inq_id)
		{
			$sql_0="SELECT id FROM lead WHERE ei_inq_id='".$inq_id."'";
			$query_0=$this->client_db->query($sql_0);
			if($query_0){
				if($query_0->num_rows()==0)
				{
					if($email!='' || $mobile!='')
					{
						if($email!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND email='".$email."'";
							$query=$this->client_db->query($sql);	
							if($query){
								if($query->num_rows()==0)
								{			
									//return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
									if($mobile!='')
									{
										$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
										$query=$this->client_db->query($sql);	
										if($query){
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
										else{
											return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
										}
																				
									} else {
										return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
									}

								}
								else if($query->num_rows()==1)
								{
									$row=$query->row();
									return array('msg'=>'one_customer_exist','customer_id'=>$row->id,'status'=>TRUE);	
								}
							}	
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}
													
						}	

						if($mobile!='')
						{
							$sql="SELECT id FROM customer WHERE status='1' AND mobile='".$mobile."'";
							$query=$this->client_db->query($sql);	
							if($query){
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
							else{
								return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
							}
																	
						}		
					}
					else
					{
						return array('msg'=>'sender_email and sender_mobile both missing','customer_id'=>'','status'=>FALSE);
					}
				}
				else
				{
					$row_0=$query_0->row();
					return array('msg'=>'inq_id already exist to the lead -'.$row_0->id,'customer_id'=>'','status'=>FALSE);
				}
			}
			else{
				return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>FALSE);
			}
			
		}
		else
		{
			return array('msg'=>'inq_id missing','customer_id'=>'','status'=>FALSE);
		}					
	}
	public function exporterindia_update($data,$id,$client_info=array())
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('exporterindia_tmp',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}
	public function exporterindia_setting_get_keyword_rule5_wise_assigned_user_id($arr,$client_info=array())
	{
		
		$assign_rule=$arr['assign_rule'];
		$exporterindia_setting_id=$arr['exporterindia_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM exporterindia_assign_rule_details WHERE ei_setting_id='".$exporterindia_setting_id."' AND assign_rule_id='".$assign_rule."'";
		$query = $this->client_db->query($sql,false);
		$return=array();
		if($query->num_rows())
		{
			foreach($query->result_array() AS $row)
			{
				$id=$row['id'];
				$find_to=unserialize($row['find_to']);
				$assign_to=unserialize($row['assign_to']);
				$assign_start=$row['assign_start'];	
				$assign_end=(count($assign_to)-1);
				if($find_to!='other')
				{         
					if(count($find_to))
					{
						for($i=0;$i<count($find_to);$i++)
						{ 
							if(strpos($search_keyword, str_replace(' ', '', strtolower($find_to[$i]))) !== false)
							{					
								$assign_start++;
								if($assign_start>$assign_end)
								{
									$assign_start=0;
								}
								break 2;
							}        
						} 
					}
				}
				else if($find_to=='other')
				{
					$assign_start++;
					if($assign_start>$assign_end)
					{
						$assign_start=0;
					}  
					break;					   
				}
			}
			$post_data=array('assign_start'=>$assign_start);
			$this->client_db->where('id',$id);
			$this->client_db->update('exporterindia_assign_rule_details',$post_data);	
			return $assign_to[$assign_start];
		}
	}

	public function exporterindia_setting_get_rule_wise_assigned_user_id($arr,$client_info=array())
	{
		

		$assign_rule=$arr['assign_rule'];
		$exporterindia_setting_id=$arr['exporterindia_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM exporterindia_assign_rule_details WHERE ei_setting_id='".$exporterindia_setting_id."' AND assign_rule_id='".$assign_rule."'";

		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query->num_rows())
		{

			foreach($query->result_array() AS $row)
			{
				$id=$row['id'];
				$find_to=unserialize($row['find_to']);
				$assign_to=unserialize($row['assign_to']);
				$assign_start=$row['assign_start'];	
				$assign_end=(count($assign_to)-1);

				if(in_array($search_keyword, $find_to))
				{
					//$tmp_assigned_user_id=$assign_to[$assign_start];
					$assign_start++;
					if($assign_start>$assign_end)
					{
						$assign_start=0;
					}

					// $post_data=array('assign_start'=>$assign_start);
					// $this->client_db->where('id',$id);
					// $this->client_db->update('indiamart_assign_rule_details',$post_data);

					// return $tmp_assigned_user_id;
					break;
				}
				else
				{
					if($find_to=='other')
					{
						//$tmp_assigned_user_id=$assign_to[$assign_start];
						$assign_start++;
						if($assign_start>$assign_end)
						{
							$assign_start=0;
						}

						// $post_data=array('assign_start'=>$assign_start);
						// $this->client_db->where('id',$id);
						// $this->client_db->update('tradeindia_assign_rule_details',$post_data);

						// return $tmp_assigned_user_id;
						break;
					}
				}
			}

			$post_data=array('assign_start'=>$assign_start);
			// echo $id.':'.$assign_start.'<br>';
			// echo $assign_to[$assign_start].'<br>';
			$this->client_db->where('id',$id);
			$this->client_db->update('exporterindia_assign_rule_details',$post_data);	
			// echo $last_query = $this->client_db->last_query();die();		
			return $assign_to[$assign_start];
		}		
	}
	public function exporterindia_setting_EditCredentials($data,$id,$client_info=array())
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('exporterindia_setting',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}
	// EXPORTERINDIA
	// ===========================================================================
    
}
?>