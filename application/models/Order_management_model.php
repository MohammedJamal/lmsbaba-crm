<?php
class Order_management_model extends CI_Model
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

	public function GetActiveStageRows()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.sort,		
		t1.is_system_generated
		FROM om_stage AS t1 	
		WHERE t1.is_active='Y' AND t1.is_deleted='N' GROUP BY t1.id ORDER BY t1.sort";
		$result=$this->client_db->query($sql);				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetActiveStageList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active,
		-- count(t2.stage_id) AS stage_wise_pi_count,
		SUM(IF(lead_opportunity_wise_po.is_cancel!='Y',1,0)) AS stage_wise_pi_count,
		form.*
		-- SUM(if(lead_opportunity_wise_po.is_cancel!='Y',1,0)) AS stage_wise_pi_count
		FROM om_stage AS t1 	
		LEFT JOIN om_po_pi_wise_stage_tag AS t2 ON t1.id=t2.stage_id 
		LEFT JOIN po_pro_forma_invoice ON po_pro_forma_invoice.id=t2.po_pi_id 
		-- LEFT JOIN po_pro_forma_invoice_split AS t1_split ON po_pro_forma_invoice.id=t1_split.po_pro_forma_invoice_id
		LEFT JOIN lead_opportunity_wise_po ON lead_opportunity_wise_po.id=po_pro_forma_invoice.lead_opportunity_wise_po_id 
		LEFT JOIN (
			SELECT om_stage_wise_assigned_form.stage_id,
					GROUP_CONCAT(concat(om_stage_form.id,'#',om_stage_form.is_mandatory)) AS form_wise_mandatory_str
			FROM  om_stage_wise_assigned_form 
			INNER JOIN om_stage_form ON om_stage_wise_assigned_form.form_id=om_stage_form.id GROUP BY om_stage_wise_assigned_form.stage_id
		) AS form ON form.stage_id=t1.id
		WHERE t1.is_active='Y' 
		-- AND lead_opportunity_wise_po.is_cancel='N'
		-- AND lead_opportunity_wise_po.is_cancel!='Y' 
		AND t1.is_deleted='N' 
		-- AND po_pro_forma_invoice.pro_forma_no!=''  
		-- AND t2.id IS NOT NULL
		GROUP BY t1.id ORDER BY t1.sort";

		
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	

	function UpdateStage($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('om_stage',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	function AddStage($data)
	{
		if($this->client_db->insert('om_stage',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	

	public function GetStage($stage_id)
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active
		FROM om_stage AS t1 		
		WHERE t1.id='".$stage_id."'";
		$result=$this->client_db->query($sql);
			
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}	
	}

	public function GetStageSortWise($sortBy)
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active
		FROM om_stage AS t1 		
		WHERE t1.sort>='".$sortBy."' AND t1.is_deleted='N' ORDER BY t1.sort";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}	
	}

	public function GetActiveStageWiseAssignUserList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active,
		group_concat(t3.name ORDER BY t3.name) AS assigned_user_name
		FROM om_stage AS t1 
		LEFT JOIN om_stage_wise_assigned_user AS t2 ON t1.id=t2.stage_id 
		LEFT JOIN user AS t3 ON t3.id=t2.user_id 
		WHERE t1.is_active='Y' AND t1.is_deleted='N' GROUP BY t1.id ORDER BY t1.sort";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetAlreadyAssignedUserIdsByTheStage($stage_id)
	{
		$sql="SELECT group_concat(user_id) AS user_ids 
				FROM om_stage_wise_assigned_user AS t1 WHERE t1.stage_id='".$stage_id."' GROUP BY t1.stage_id";
		$result=$this->client_db->query($sql);				
		if($result){
			return $result->row()->user_ids;
		}
		else{
			return '';
		}
	}

	public function GetNotAssignedUserIdsByTheStage($user_ids='')
	{
		$subsql ="";
		if($user_ids){
			$subsql .=" AND t1.id NOT IN ($user_ids)";
		}
		$sql="SELECT 
		t1.id,
		t1.name
		FROM user AS t1 		
		WHERE t1.status='0' $subsql ORDER BY t1.name";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetUsersByIds($user_ids='')
	{
		$subsql ="";
		if($user_ids){
			$subsql .=" AND t1.id IN ($user_ids)";
		}
		$sql="SELECT 
		t1.id,
		t1.name
		FROM user AS t1 		
		WHERE t1.status='0' $subsql ORDER BY t1.name";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function add_stage_wise_assigned_user($data)
	{
		if($this->client_db->insert('om_stage_wise_assigned_user',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function delete_stage_wise_assigned_user_by_stageIdAndUserId($stage_id='',$user_id='')
	{
		if($stage_id!='' && $user_id!=''){
			$this->client_db->where('stage_id', $stage_id);
			$this->client_db->where('user_id', $user_id);
			$this->client_db->delete('om_stage_wise_assigned_user');
		}		
		return true;
	}

	public function GetAssignedUserIdsByTheStage($user_ids='')
	{
		$subsql ="";
		if($user_ids){
			$subsql .=" AND t1.id IN ($user_ids)";
			$sql="SELECT 
			t1.id,
			t1.name
			FROM user AS t1 		
			WHERE t1.status='0' $subsql ORDER BY t1.name";
			$result=$this->client_db->query($sql);
					
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
		
	}

	public function GetTaggedPiWiseStageList()
	{
		$sql="SELECT 
		t1.id,
		t1.stage_id,
		t1.po_pi_id,		
		t1.updated_by,
		t1.created_at
		FROM om_po_pi_wise_stage_tag AS t1 		
		WHERE 1=1 ORDER BY t1.id";
		$result=$this->client_db->query($sql);				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetTaggedPiList()
	{
		// $sql="SELECT 
		// 	t1.id,
		// 	t1.lead_opportunity_wise_po_id,
		// 	t1.proforma_type,
		// 	t1.po_custom_proforma,
		// 	t1.pro_forma_no,
		// 	t1.pro_forma_date,
		// 	t1.due_date,
		// 	t1.expected_delivery_date,
		// 	t1.bill_from,
		// 	t1.bill_to,
		// 	t1.ship_to,
		// 	t1.terms_conditions,
		// 	t1.additional_note,
		// 	t1.bank_detail_1,
		// 	t1.bank_detail_2,
		// 	t1.is_digital_signature_checked,
		// 	t1.name_of_authorised_signature,
		// 	t1.thanks_and_regards,
		// 	t1.currency_type,
		// 	t1.total_pro_forma_inv_amount,
		// 	t1.created_at,
		// 	t1.updated_at,
		// 	t2.po_number,
		// 	t2.po_date,
		// 	t2.is_cancel,
		// 	t2.cancelled_date,
		// 	t2.cancelled_by,
		// 	t2.po_tds_percentage,
		// 	t3.lead_id,
		// 	t3.deal_value_as_per_purchase_order,
		// 	lead.id AS l_id,
		// 	lead.title AS l_title,
		// 	lead.customer_id AS l_customer_id,
		// 	lead.assigned_user_id AS l_assigned_user_id,
		// 	lead.buying_requirement AS l_buying_requirement,
		// 	lead.enquiry_date AS l_enquiry_date,
		// 	lead.followup_date AS l_followup_date,
		// 	lead.modify_date AS l_modify_date,
		// 	lead.current_stage_id AS l_current_stage_id,			
		// 	lead.current_stage AS l_current_stage,
		// 	lead.current_status AS l_current_status,
		// 	lead.is_hotstar AS l_is_hotstar,
		// 	c_r_c.*,
		// 	c_paying_info.*,
		// 	cus.id AS cus_id,
		// 	cus.first_name AS cus_first_name,
		// 	cus.last_name AS cus_last_name,
		// 	cus.mobile_country_code AS cus_mobile_country_code,
		// 	cus.mobile AS cus_mobile,
		// 	cus.mobile_whatsapp_status AS cus_mobile_whatsapp_status,
		// 	cus.email AS cus_email,
		// 	cus.website AS cus_website,
		// 	cus.contact_person AS cus_contact_person,
		// 	cus.company_name AS cus_company_name,
		// 	source.name AS source_name,
		// 	user.name AS assigned_user_name,
		// 	countries.name AS cust_country_name,
		// 	states.name AS cust_state_name,
		// 	cities.name AS cust_city_name,
		// 	l_opp_currency.code AS lead_opp_currency_code,
		// 	payment_term.*,			
		// 	po_invoice.id AS invoice_id,
		// 	po_invoice.invoice_no,
		// 	po_invoice.invoice_date,
		// 	SUM(po_payment_received.amount) AS payment_received,
		// 	om_po_pi_wise_stage_tag.id AS om_po_pi_wise_stage_tag_id,
		// 	om_po_pi_wise_stage_tag.stage_id AS pi_stage_id,
		// 	om_po_pi_wise_stage_tag.priority AS pi_priority
		// 	FROM po_pro_forma_invoice AS t1 
		// 	INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
		// 	INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  
		// 	INNER JOIN currency AS l_opp_currency ON t3.currency_type=l_opp_currency.id
		// 	INNER JOIN lead ON t3.lead_id=lead.id 
		// 	LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id 
		// 	LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id 
		// 	INNER JOIN customer AS cus ON cus.id=lead.customer_id 
		// 	INNER JOIN source ON source.id=lead.source_id 
		// 	INNER JOIN user ON user.id=lead.assigned_user_id 
		// 	LEFT JOIN countries ON countries.id=cus.country_id 
		// 	LEFT JOIN states ON states.id=cus.state 
		// 	LEFT JOIN cities ON cities.id=cus.city 
		// 	LEFT JOIN 
		// 	(
		// 		SELECT 
		// 		table1.lead_opportunity_wise_po_id AS lowp,
		// 		table1.payment_type,
		// 		table1.total_amount,
		// 		SUM(table2.amount) AS total_payble_amount
		// 		FROM po_payment_terms AS table1 
		// 		INNER JOIN po_payment_term_details AS table2 ON table1.id=table2.po_payment_term_id GROUP BY table2.po_payment_term_id

		// 	) AS payment_term ON payment_term.lowp=t2.id 			
		// 	LEFT JOIN po_invoice ON po_invoice.lead_opportunity_wise_po_id=t2.id 
		// 	LEFT JOIN po_payment_received ON po_payment_received.lead_opportunity_wise_po_id=t2.id 
		// 	LEFT JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id
		// 	WHERE t1.pro_forma_no!='' AND om_po_pi_wise_stage_tag.id IS NOT NULL GROUP BY t1.lead_opportunity_wise_po_id ORDER BY om_po_pi_wise_stage_tag.priority DESC,t2.id ASC";

		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.expected_delivery_date,
			t2.po_date,
			t2.is_cancel,
			t2.cancelled_date,
			t2.cancelled_by,
			-- t1_split.id AS split_id,
			cus.contact_person AS cus_contact_person,
			cus.company_name AS cus_company_name,	
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,			
			om_po_pi_wise_stage_tag.id AS om_po_pi_wise_stage_tag_id,
			om_po_pi_wise_stage_tag.stage_id AS pi_stage_id,
			om_po_pi_wise_stage_tag.po_pi_split_id AS split_id,
			om_po_pi_wise_stage_tag.priority AS pi_priority,
			GROUP_CONCAT(om_stage_form_submitted.form_id) AS submitted_form_id
			FROM po_pro_forma_invoice AS t1 
			-- LEFT JOIN po_pro_forma_invoice_split AS t1_split ON t1.id=t1_split.po_pro_forma_invoice_id
			INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
			INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  			
			INNER JOIN lead ON t3.lead_id=lead.id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 			
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city  			 
			INNER JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id
			LEFT JOIN om_stage_form_submitted ON om_stage_form_submitted.po_pi_id=t1.id AND  
            om_stage_form_submitted.pro_forma_invoice_split_id = (CASE WHEN om_po_pi_wise_stage_tag.po_pi_split_id IS NOT NULL THEN om_po_pi_wise_stage_tag.po_pi_split_id ELSE '' END)   
			WHERE t1.pro_forma_no!='' AND t2.is_cancel='N' AND om_po_pi_wise_stage_tag.id IS NOT NULL GROUP BY om_po_pi_wise_stage_tag.id ORDER BY om_po_pi_wise_stage_tag.priority DESC,t1.id DESC LIMIT 0,20";

		// echo $sql; die();
		$result=$this->client_db->query($sql);				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetUnTaggedPiList()
	{
		// $sql="SELECT 
		// 	t1.id,
		// 	t1.lead_opportunity_wise_po_id,
		// 	t1.proforma_type,
		// 	t1.po_custom_proforma,
		// 	t1.pro_forma_no,
		// 	t1.pro_forma_date,
		// 	t1.due_date,
		// 	t1.expected_delivery_date,
		// 	t1.bill_from,
		// 	t1.bill_to,
		// 	t1.ship_to,
		// 	t1.terms_conditions,
		// 	t1.additional_note,
		// 	t1.bank_detail_1,
		// 	t1.bank_detail_2,
		// 	t1.is_digital_signature_checked,
		// 	t1.name_of_authorised_signature,
		// 	t1.thanks_and_regards,
		// 	t1.currency_type,
		// 	t1.total_pro_forma_inv_amount,
		// 	t1.created_at,
		// 	t1.updated_at,
		// 	t2.po_number,
		// 	t2.po_date,
		// 	t2.is_cancel,
		// 	t2.cancelled_date,
		// 	t2.cancelled_by,
		// 	t2.po_tds_percentage,
		// 	t3.lead_id,
		// 	t3.deal_value_as_per_purchase_order,
		// 	lead.id AS l_id,
		// 	lead.title AS l_title,
		// 	lead.customer_id AS l_customer_id,
		// 	lead.assigned_user_id AS l_assigned_user_id,
		// 	lead.buying_requirement AS l_buying_requirement,
		// 	lead.enquiry_date AS l_enquiry_date,
		// 	lead.followup_date AS l_followup_date,
		// 	lead.modify_date AS l_modify_date,
		// 	lead.current_stage_id AS l_current_stage_id,			
		// 	lead.current_stage AS l_current_stage,
		// 	lead.current_status AS l_current_status,
		// 	lead.is_hotstar AS l_is_hotstar,
		// 	c_r_c.*,
		// 	c_paying_info.*,
		// 	cus.id AS cus_id,
		// 	cus.first_name AS cus_first_name,
		// 	cus.last_name AS cus_last_name,
		// 	cus.mobile_country_code AS cus_mobile_country_code,
		// 	cus.mobile AS cus_mobile,
		// 	cus.mobile_whatsapp_status AS cus_mobile_whatsapp_status,
		// 	cus.email AS cus_email,
		// 	cus.website AS cus_website,
		// 	cus.contact_person AS cus_contact_person,
		// 	cus.company_name AS cus_company_name,
		// 	source.name AS source_name,
		// 	user.name AS assigned_user_name,
		// 	countries.name AS cust_country_name,
		// 	states.name AS cust_state_name,
		// 	cities.name AS cust_city_name,
		// 	l_opp_currency.code AS lead_opp_currency_code,
		// 	payment_term.*,			
		// 	po_invoice.id AS invoice_id,
		// 	po_invoice.invoice_no,
		// 	po_invoice.invoice_date,
		// 	SUM(po_payment_received.amount) AS payment_received 			
		// 	FROM po_pro_forma_invoice AS t1 
		// 	INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
		// 	INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  
		// 	INNER JOIN currency AS l_opp_currency ON t3.currency_type=l_opp_currency.id
		// 	INNER JOIN lead ON t3.lead_id=lead.id 
		// 	LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id 
		// 	LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id 
		// 	INNER JOIN customer AS cus ON cus.id=lead.customer_id 
		// 	INNER JOIN source ON source.id=lead.source_id 
		// 	INNER JOIN user ON user.id=lead.assigned_user_id 
		// 	LEFT JOIN countries ON countries.id=cus.country_id 
		// 	LEFT JOIN states ON states.id=cus.state 
		// 	LEFT JOIN cities ON cities.id=cus.city 
		// 	LEFT JOIN 
		// 	(
		// 		SELECT 
		// 		table1.lead_opportunity_wise_po_id AS lowp,
		// 		table1.payment_type,
		// 		table1.total_amount,
		// 		SUM(table2.amount) AS total_payble_amount
		// 		FROM po_payment_terms AS table1 
		// 		INNER JOIN po_payment_term_details AS table2 ON table1.id=table2.po_payment_term_id GROUP BY table2.po_payment_term_id

		// 	) AS payment_term ON payment_term.lowp=t2.id 			
		// 	LEFT JOIN po_invoice ON po_invoice.lead_opportunity_wise_po_id=t2.id 
		// 	LEFT JOIN po_payment_received ON po_payment_received.lead_opportunity_wise_po_id=t2.id 
		// 	LEFT JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id
		// 	WHERE t1.pro_forma_no!='' AND om_po_pi_wise_stage_tag.id IS NULL GROUP BY t1.lead_opportunity_wise_po_id ORDER BY t2.id ASC";

		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,			
			lead.assigned_user_id AS l_assigned_user_id			
			FROM po_pro_forma_invoice AS t1 
			INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
			INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id 			
			INNER JOIN lead ON t3.lead_id=lead.id 			
			LEFT JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id
			WHERE t1.pro_forma_no!='' AND om_po_pi_wise_stage_tag.id IS NULL GROUP BY t1.lead_opportunity_wise_po_id ORDER BY t1.id ASC";
		// $sql="SELECT 
		// 	t1.id,
		// 	t1.lead_opportunity_wise_po_id,			
		// 	lead.assigned_user_id AS l_assigned_user_id			
		// 	FROM po_pro_forma_invoice AS t1 
		// 	INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 		
		// 	LEFT JOIN po_pro_forma_invoice_split AS t1_split ON t1.id=t1_split.po_pro_forma_invoice_id	
		// 	INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id 			
		// 	INNER JOIN lead ON t3.lead_id=lead.id 			
		// 	LEFT JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id
		// 	WHERE t1.pro_forma_no!='' ORDER BY t1.id ASC";
		// 	echo $sql; die();	
		$result=$this->client_db->query($sql);				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function delete_existing_pi_stage($pi_id='',$prev_stage_id='')
	{
		if($pi_id!='' && $prev_stage_id!=''){
			// $this->client_db->where('stage_id', $prev_stage_id);
			$this->client_db->where('po_pi_id', $pi_id);
			$this->client_db->delete('om_po_pi_wise_stage_tag');
		}		
		return true;
	}

	function add_pi_stage_tag($data)
	{
		if($this->client_db->insert('om_po_pi_wise_stage_tag',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function update_pi_stage_tag($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('om_po_pi_wise_stage_tag',$data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	function add_pi_stage_tag_log($data)
	{
		if($this->client_db->insert('om_po_pi_wise_stage_tag_log',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function is_existing_pi_stage($pi_id,$pfi_split_id='')
	{
		$subsql="";
		if($pfi_split_id){
			$subsql .=" AND t1.po_pi_split_id='".$pfi_split_id."'";
		}
		$sql="SELECT t1.id FROM om_po_pi_wise_stage_tag AS t1 WHERE t1.po_pi_id='".$pi_id."' $subsql";
		$result=$this->client_db->query($sql);				
		if($result){
			if($result->num_rows()){
				return $result->row()->id;
			}
			else{
				return 0;
			}
		}
		else{
			return 0;
		}
	}

	function AddStageForm($data)
	{
		if($this->client_db->insert('om_stage_form',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function GetStageFormList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.is_mandatory,
		t1.is_active
		FROM om_stage_form AS t1 		
		WHERE t1.is_deleted='N' ORDER BY t1.id";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function UpdateStageForm($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('om_stage_form',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function GetStageFormRow($id='')
	{		
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.is_active
		FROM om_stage_form AS t1 		
		WHERE t1.id='".$id."'";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	public function GetStageFormFieldsList($form_id)
	{
		$sql="SELECT 
		t1.id,
		t1.form_id,
		t1.input_type,
		t1.label,
		t1.option_with_value,		
		t1.is_mandatory,
		t1.sort,
		t2.name
		FROM om_stage_form_field AS t1 		
		LEFT JOIN om_stage_form AS t2 ON t1.form_id=t2.id
		WHERE t1.form_id='".$form_id."' AND t1.is_deleted='N' ORDER BY t1.sort";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetLastSortFormFields($form_id='')
	{
		if($form_id){
			$sql="SELECT t1.sort
			FROM om_stage_form_field AS t1 	
			WHERE t1.form_id='".$form_id."' AND t1.is_deleted='N' ORDER BY t1.sort DESC LIMIT 1";
			$result=$this->client_db->query($sql);				
			if($result){
				return $result->row()->sort;
			}
			else{
				return 0;
			}
		}
		else{
			return 0;
		}
		
	}

	
	function AddStageFormFields($data)
	{
		if($this->client_db->insert('om_stage_form_field',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function UpdateStageFormFields($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('om_stage_form_field',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function GetAlreadyAssignedFormIdsByTheStage($stage_id)
	{
		$sql="SELECT 
				group_concat(form_id) AS form_ids 
			FROM om_stage_wise_assigned_form AS t1 
			WHERE t1.stage_id='".$stage_id."' GROUP BY t1.stage_id";
		$result=$this->client_db->query($sql);				
		if($result){
			return $result->row()->form_ids;
		}
		else{
			return '';
		}
	}

	public function GetStageFormById($id='')
	{
		
		if($id){
			$subsql ="";
			$subsql .=" AND t1.id ='".$id."'";
			$sql="SELECT 
			t1.id,
			t1.name
			FROM om_stage_form AS t1 		
			WHERE  t1.is_deleted='N' $subsql ";
			$result=$this->client_db->query($sql);
					
			if($result){
				return $result->row_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
		
	}

	public function GetNotAssignedFormIdsByTheStage($form_ids='')
	{
		$subsql ="";
		if($form_ids){
			$subsql .=" AND t1.id NOT IN ($form_ids)";
		}
		$sql="SELECT 
		t1.id,
		t1.name
		FROM om_stage_form AS t1 		
		WHERE t1.is_active='Y' AND t1.is_deleted='N' AND t1.id NOT IN (select form_id from om_stage_wise_assigned_form) $subsql ORDER BY t1.name";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetActiveStageWiseAssignFormList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active,
		group_concat(t3.name ORDER BY t3.name) AS assigned_user_name
		FROM om_stage AS t1 
		LEFT JOIN om_stage_wise_assigned_form AS t2 ON t1.id=t2.stage_id 
		LEFT JOIN om_stage_form AS t3 ON t3.id=t2.form_id  AND t3.is_active='Y' AND t3.is_deleted='N' 
		WHERE t1.is_active='Y' AND t1.is_deleted='N' GROUP BY t1.id ORDER BY t1.sort";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function add_stage_wise_assigned_form($data)
	{
		if($this->client_db->insert('om_stage_wise_assigned_form',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}
	public function GetAssignedFormIdsByTheStage($form_ids='')
	{
		$subsql ="";
		if($form_ids){
			$subsql .=" AND t1.id IN ($form_ids)";
			$sql="SELECT 
			t1.id,
			t1.name
			FROM om_stage_form AS t1 		
			WHERE t1.is_active='Y' AND t1.is_deleted='N' $subsql ORDER BY t1.name";
			$result=$this->client_db->query($sql);
					
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
		
	}

	public function delete_stage_wise_assigned_form_by_stageIdAndUserId($stage_id='',$form_id='')
	{
		if($stage_id!='' && $form_id!=''){
			$this->client_db->where('stage_id', $stage_id);
			$this->client_db->where('form_id', $form_id);
			$this->client_db->delete('om_stage_wise_assigned_form');
		}		
		return true;
	}

	public function GetFormByTheStage($stage_id)
	{
		$sql="SELECT t1.form_id,t2.name
			FROM om_stage_wise_assigned_form AS t1 
			INNER JOIN om_stage_form AS t2 ON t1.form_id=t2.id 
			WHERE t1.stage_id='".$stage_id."' AND t2.is_active='Y' AND t2.is_deleted='N' ORDER BY t2.name";
		$result=$this->client_db->query($sql);				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	
	public function GetActiveStageFormFieldsList($form_id='')
	{
		if($form_id){
			$sql="SELECT 
			t1.id,
			t1.form_id,
			t1.input_type,
			t1.label,
			t1.option_with_value,		
			t1.is_mandatory,
			t1.sort,
			t2.name
			FROM om_stage_form_field AS t1 		
			LEFT JOIN om_stage_form AS t2 ON t1.form_id=t2.id
			WHERE t1.form_id='".$form_id."' AND t1.is_active='Y' AND t1.is_deleted='N' ORDER BY t1.sort";
			$result=$this->client_db->query($sql);
					
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
		
	}

	public function GetFormFieldByIDRow($id='')
	{
		if($id){
			$sql="SELECT 
			t1.input_type,
			t1.label,
			t1.option_with_value,		
			t1.is_mandatory,
			t1.sort
			FROM om_stage_form_field AS t1 	
			WHERE t1.id='".$id."' AND t1.is_active='Y' AND t1.is_deleted='N'";
			$result=$this->client_db->query($sql);					
			if($result){
				return $result->row_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
		
	}

	function AddStageFormSubmitted($data)
	{
		if($this->client_db->insert('om_stage_form_submitted',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function AddStageFormFieldsSubmitted($data)
	{
		if($this->client_db->insert('om_stage_form_field_submitted',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function UpdateStageFormFieldsSubmitted($data,$id='')
	{
		if($id){
			$this->client_db->where('id',$id);
			if($this->client_db->update('om_stage_form_field_submitted',$data))
			{
				return true;		  
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
	}

	function UpdateStageFormFieldsSubmittedBy_om_stage_form_field_id($data,$om_stage_form_field_id='',$om_stage_form_submitted_id='')
	{
		if($om_stage_form_field_id!='' && $om_stage_form_submitted_id!=''){
			$this->client_db->where('om_stage_form_field_id',$om_stage_form_field_id);
			$this->client_db->where('om_stage_form_submitted_id',$om_stage_form_submitted_id);
			if($this->client_db->update('om_stage_form_field_submitted',$data))
			{
				return true;		  
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
	}

	public function GetPIWiseDocumentList($pf_id='',$pfi_split_id='')
	{
		if($pf_id){
			$subsql="";
			if($pfi_split_id){
				$subsql .= " AND t1.pro_forma_invoice_split_id='".$pfi_split_id."'";
			}
			$sql="SELECT 
			t1.id,
			t1.form_id,
			t1.name,
			t1.user_id,
			t1.po_pi_id
			FROM om_stage_form_submitted AS t1 	
			WHERE t1.po_pi_id='".$pf_id."' $subsql ORDER BY t1.name";
			$result=$this->client_db->query($sql);					
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
		
	}

	public function GetSubmittedFormFieldsList($om_stage_form_submitted_id='')
	{
		if($om_stage_form_submitted_id){
			$sql="SELECT 
			t1.id,
			t1.om_stage_form_field_id,
			t1.om_stage_form_submitted_id,
			t1.input_type,
			t1.label,
			t1.option_with_value,		
			t1.is_mandatory,
			t1.sort,
			t1.submitted_value,
			t2.form_id,
			t2.name,			
			t2.user_id,
			t2.po_pi_id
			FROM om_stage_form_field_submitted AS t1 		
			LEFT JOIN om_stage_form_submitted AS t2 ON t1.om_stage_form_submitted_id=t2.id
			WHERE t1.om_stage_form_submitted_id='".$om_stage_form_submitted_id."' ORDER BY t1.sort";
			$result=$this->client_db->query($sql);
					
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
		
	}

	public function delete_submitted_document($id='')
	{
		if($id!=''){
			
			$this->client_db->where('om_stage_form_submitted_id', $id);
			$this->client_db->delete('om_stage_form_field_submitted');

			$this->client_db->where('id', $id);
			$this->client_db->delete('om_stage_form_submitted');

			return true;
		}	
		else{
			return false;
		}	
		
	}

	public function ChkIsFirmAlreadyExist($form_id='',$proforma_invoice_id='',$pfi_split_id='')
	{
		if($form_id!='' && $proforma_invoice_id!=''){
			$subsql="";
			if($pfi_split_id){
				$subsql .=" AND t1.pro_forma_invoice_split_id='".$pfi_split_id."'";
			}
			$sql="SELECT t1.id
			FROM om_stage_form_submitted AS t1 
			WHERE t1.form_id='".$form_id."' AND t1.po_pi_id='".$proforma_invoice_id."' $subsql";
			$result=$this->client_db->query($sql);
					
			if($result){
				if($result->num_rows()){
					return $result->row()->id;
				}
				else{
					return 0;
				}
			}
			else{
				return 0;
			}
		}
		else{
			return 0;
		}
		
	}

	public function get_submitted_document_list_by_om_stage_form_submitted_id($om_stage_form_submitted_id='')
	{
		if($om_stage_form_submitted_id!=''){

			$sql="SELECT t1.input_type,t1.submitted_value
			FROM om_stage_form_field_submitted AS t1 		
			WHERE t1.om_stage_form_submitted_id='".$om_stage_form_submitted_id."' ORDER BY t1.sort";
			
			$result=$this->client_db->query($sql);					
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}		
	}
	public function chk_duplicate_stage_wise_assigned_form($stage_id='',$form_id='')
	{
		if($stage_id>0 && $form_id>0){
			$sql="SELECT t1.id
			FROM om_stage_wise_assigned_form AS t1 
			WHERE t1.stage_id='".$stage_id."' AND t1.form_id='".$form_id."'";
			$result=$this->client_db->query($sql);					
			if($result){
				if($result->num_rows()){
					return $result->row()->id;
				}
				else{
					return 0;
				}
			}
			else{
				return 0;
			}
		}
		else{
			return 0;
		}
		
	}

	public function chk_duplicate_om_stage_form_submitted($form_id='',$po_pi_id='',$pfi_split_id='')
	{
		if($form_id!='' && $po_pi_id!=''){
			$subsql="";
			if($pfi_split_id){
				$subsql .=" AND t1.pro_forma_invoice_split_id='".$pfi_split_id."'";
			}
			$sql="SELECT t1.id
			FROM om_stage_form_submitted AS t1 
			WHERE t1.form_id='".$form_id."' AND t1.po_pi_id='".$po_pi_id."' $subsql";
			$result=$this->client_db->query($sql);					
			if($result){
				if($result->num_rows()){
					return $result->row()->id;
				}
				else{
					return 0;
				}
			}
			else{
				return 0;
			}
		}
		else{
			return 0;
		}
		
	}

	public function get_om_stage_form_submitted_row($id='')
	{
		if($id!=''){
			$sql="SELECT t1.id,t1.form_id,t1.name,t1.user_id,t1.po_pi_id
			FROM om_stage_form_submitted AS t1 
			WHERE t1.id='".$id."' ";
			$result=$this->client_db->query($sql);					
			if($result){
				if($result->num_rows()){
					return $result->row_array();
				}
				else{
					return array();
				}
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
		
	}

	function update_po_pi_wise_stage_tag($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('om_po_pi_wise_stage_tag',$data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function GetExistingPiPriority($id='')
	{
		if($id){
			$sql="SELECT 
			t1.priority
			FROM om_po_pi_wise_stage_tag AS t1 WHERE t1.id='".$id."'";
			$result=$this->client_db->query($sql);					
			if($result){
				return $result->row()->priority;
			}
			else{
				return '';
			}
		}
		else{
			return '';
		}		
	}

	public function GetExistingPiLockStatus($id='')
	{
		if($id){
			$sql="SELECT 
			t1.is_lock
			FROM om_po_pi_wise_stage_tag AS t1 WHERE t1.id='".$id."'";
			$result=$this->client_db->query($sql);					
			if($result){
				return $result->row()->is_lock;
			}
			else{
				return '';
			}
		}
		else{
			return '';
		}		
	}

	public function GetPiWithTag($id='',$pfi_split_id='')
	{
		if($id){
			$subsql='';
			if($pfi_split_id){
				$subsql .=" AND om_po_pi_wise_stage_tag.po_pi_split_id='".$pfi_split_id."'";
			}
				$sql="SELECT 
				t1.id,
				t1.lead_opportunity_wise_po_id,				
				om_po_pi_wise_stage_tag.id AS om_po_pi_wise_stage_tag_id,
				om_po_pi_wise_stage_tag.stage_id,
				om_po_pi_wise_stage_tag.priority
				FROM po_pro_forma_invoice AS t1 						 
				INNER JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id
				WHERE t1.id='".$id."' $subsql";			
			$result=$this->client_db->query($sql);				
			if($result){
				return $result->row_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
				
	}

	public function GetPiCompletedStageIds($om_po_pi_wise_stage_tag_id='')
	{				
		if($om_po_pi_wise_stage_tag_id){
			$sql="SELECT GROUP_CONCAT(t3.id ORDER BY t1.id,t3.sort) AS completed_statge_ids 
			FROM `om_po_pi_wise_stage_tag` AS t1 
			INNER JOIN om_stage AS t2 ON t1.stage_id = t2.id
			INNER JOIN om_stage AS t3 ON t3.sort<=t2.sort AND t3.is_deleted='N' 
			WHERE t1.id='".$om_po_pi_wise_stage_tag_id."' ORDER BY t1.id,t3.sort";
			
			$result=$this->client_db->query($sql);				
			if($result){
				return $result->row()->completed_statge_ids;
			}
			else{
				return '';
			}
		}
		else{
			return '';
		}
		
	}

	function CreateHistory($data)
	{
		if($this->client_db->insert('om_history',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function GetDataForHistoryByPi($id='')
	{
		if($id){
				$sql="SELECT 
				t1.id AS po_pi_id,				
				lead_opportunity.id AS lead_opportunity_id,
				lead_opportunity.lead_id,
				lead_opportunity_wise_po.id AS lowp,
				lead.source_id
				FROM po_pro_forma_invoice AS t1 						 
				INNER JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id
				LEFT JOIN lead_opportunity_wise_po ON lead_opportunity_wise_po.id=t1.lead_opportunity_wise_po_id 
				LEFT JOIN lead_opportunity ON lead_opportunity.id=lead_opportunity_wise_po.lead_opportunity_id 
				LEFT JOIN lead ON lead.id=lead_opportunity.lead_id
				WHERE t1.id='".$id."'";		
			$result=$this->client_db->query($sql);				
			if($result){
				return $result->row_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
				
	}

	public function GetOrderHistory($arg_arr=array())
	{
		if(count($arg_arr)){
				
				$subsql='';
				if(isset($arg_arr['po_pi_id'])){
					$subsql .=' AND om_history.po_pi_id='.$arg_arr['po_pi_id'];
				}
				
				if(isset($arg_arr['user_id'])){
					// $subsql .=" AND om_history.updated_by='".$arg_arr['user_id']."'";
				}

				$sql="SELECT 	
					om_history.title,				
					om_history.comment,
					om_history.created_at,
					om_history.ip_address,					
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
					source.alias_name as source_alias_name,
					p_invoice.proforma_type,					
					p_invoice.pro_forma_no,
					p_invoice.pro_forma_date,
					p_invoice.due_date AS pro_forma_due_date,
					p_invoice.expected_delivery_date AS pro_forma_expected_delivery_date
					FROM om_history 
					LEFT JOIN lead on om_history.lead_id=lead.id
					LEFT JOIN lead_opportunity AS quo on om_history.lead_opportunity_id=quo.id 
					INNER JOIN customer as cus on cus.id=lead.customer_id 
					INNER JOIN source on source.id=lead.source_id 
					LEFT JOIN user on user.id=lead.assigned_user_id
					LEFT JOIN user AS u2 on u2.id=om_history.updated_by 
					LEFT JOIN po_pro_forma_invoice AS p_invoice ON om_history.po_pi_id=p_invoice.id 
					WHERE 1=1 $subsql ORDER BY om_history.id DESC";	//echo $sql; die();
			$result=$this->client_db->query($sql);				
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
				
	}

	public function GetTaggedPiListPagination_count($argument=NULL)
	{
		
		$result = array();       
        $subsql = '';   
		$subsqlInner='';
        if($argument['stage_id']){
			$subsql .= " AND om_po_pi_wise_stage_tag.stage_id='".$argument['stage_id']."'";
		}
		if($argument['string_search']){
			$priority = (strtolower($argument['string_search'])=='normal')?'1':'2';
			$subsql .= " AND (t1.lead_opportunity_wise_po_id='".$argument['string_search']."' || om_stage.name LIKE '%".$argument['string_search']."%' || cus.company_name LIKE '%".$argument['string_search']."%' || countries.name LIKE '%".$argument['string_search']."%' || states.name LIKE '%".$argument['string_search']."%' || cities.name LIKE '%".$argument['string_search']."%' || om_po_pi_wise_stage_tag.priority = '".$priority."')";
		}
		$sql="SELECT 
			t1.id
			FROM po_pro_forma_invoice AS t1 
			INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
			INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  			
			INNER JOIN lead ON t3.lead_id=lead.id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 			
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city  			 
			LEFT JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id 
			LEFT JOIN om_stage ON om_stage.id=om_po_pi_wise_stage_tag.stage_id 
			LEFT JOIN om_stage_form_submitted ON om_stage_form_submitted.po_pi_id=t1.id AND  
            om_stage_form_submitted.pro_forma_invoice_split_id = (CASE WHEN om_po_pi_wise_stage_tag.po_pi_split_id IS NOT NULL THEN om_po_pi_wise_stage_tag.po_pi_split_id ELSE '' END)
			WHERE t1.pro_forma_no!='' AND t2.is_cancel='N' AND om_po_pi_wise_stage_tag.id IS NOT NULL  $subsql GROUP BY om_po_pi_wise_stage_tag.id";

		
		$result=$this->client_db->query($sql);				
		if($result){
			return $result->num_rows();
		}
		else{
			return 0;
		}
	}
	public function GetTaggedPiListPagination($argument=NULL)
	{
		// $sql="SELECT 
		// 	t1.id,
		// 	t1.lead_opportunity_wise_po_id,
		// 	t1.proforma_type,
		// 	t1.po_custom_proforma,
		// 	t1.pro_forma_no,
		// 	t1.pro_forma_date,
		// 	t1.due_date,
		// 	t1.expected_delivery_date,
		// 	t1.bill_from,
		// 	t1.bill_to,
		// 	t1.ship_to,
		// 	t1.terms_conditions,
		// 	t1.additional_note,
		// 	t1.bank_detail_1,
		// 	t1.bank_detail_2,
		// 	t1.is_digital_signature_checked,
		// 	t1.name_of_authorised_signature,
		// 	t1.thanks_and_regards,
		// 	t1.currency_type,
		// 	t1.total_pro_forma_inv_amount,
		// 	t1.created_at,
		// 	t1.updated_at,
		// 	t2.po_number,
		// 	t2.po_date,
		// 	t2.is_cancel,
		// 	t2.cancelled_date,
		// 	t2.cancelled_by,
		// 	t2.po_tds_percentage,
		// 	t3.lead_id,
		// 	t3.deal_value_as_per_purchase_order,
		// 	lead.id AS l_id,
		// 	lead.title AS l_title,
		// 	lead.customer_id AS l_customer_id,
		// 	lead.assigned_user_id AS l_assigned_user_id,
		// 	lead.buying_requirement AS l_buying_requirement,
		// 	lead.enquiry_date AS l_enquiry_date,
		// 	lead.followup_date AS l_followup_date,
		// 	lead.modify_date AS l_modify_date,
		// 	lead.current_stage_id AS l_current_stage_id,			
		// 	lead.current_stage AS l_current_stage,
		// 	lead.current_status AS l_current_status,
		// 	lead.is_hotstar AS l_is_hotstar,
		// 	c_r_c.*,
		// 	c_paying_info.*,
		// 	cus.id AS cus_id,
		// 	cus.first_name AS cus_first_name,
		// 	cus.last_name AS cus_last_name,
		// 	cus.mobile_country_code AS cus_mobile_country_code,
		// 	cus.mobile AS cus_mobile,
		// 	cus.mobile_whatsapp_status AS cus_mobile_whatsapp_status,
		// 	cus.email AS cus_email,
		// 	cus.website AS cus_website,
		// 	cus.contact_person AS cus_contact_person,
		// 	cus.company_name AS cus_company_name,
		// 	source.name AS source_name,
		// 	user.name AS assigned_user_name,
		// 	countries.name AS cust_country_name,
		// 	states.name AS cust_state_name,
		// 	cities.name AS cust_city_name,
		// 	l_opp_currency.code AS lead_opp_currency_code,
		// 	payment_term.*,			
		// 	po_invoice.id AS invoice_id,
		// 	po_invoice.invoice_no,
		// 	po_invoice.invoice_date,
		// 	SUM(po_payment_received.amount) AS payment_received,
		// 	om_po_pi_wise_stage_tag.id AS om_po_pi_wise_stage_tag_id,
		// 	om_po_pi_wise_stage_tag.stage_id AS pi_stage_id,
		// 	om_po_pi_wise_stage_tag.priority AS pi_priority
		// 	FROM po_pro_forma_invoice AS t1 
		// 	INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
		// 	INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  
		// 	INNER JOIN currency AS l_opp_currency ON t3.currency_type=l_opp_currency.id
		// 	INNER JOIN lead ON t3.lead_id=lead.id 
		// 	LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id 
		// 	LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id 
		// 	INNER JOIN customer AS cus ON cus.id=lead.customer_id 
		// 	INNER JOIN source ON source.id=lead.source_id 
		// 	INNER JOIN user ON user.id=lead.assigned_user_id 
		// 	LEFT JOIN countries ON countries.id=cus.country_id 
		// 	LEFT JOIN states ON states.id=cus.state 
		// 	LEFT JOIN cities ON cities.id=cus.city 
		// 	LEFT JOIN 
		// 	(
		// 		SELECT 
		// 		table1.lead_opportunity_wise_po_id AS lowp,
		// 		table1.payment_type,
		// 		table1.total_amount,
		// 		SUM(table2.amount) AS total_payble_amount
		// 		FROM po_payment_terms AS table1 
		// 		INNER JOIN po_payment_term_details AS table2 ON table1.id=table2.po_payment_term_id GROUP BY table2.po_payment_term_id

		// 	) AS payment_term ON payment_term.lowp=t2.id 			
		// 	LEFT JOIN po_invoice ON po_invoice.lead_opportunity_wise_po_id=t2.id 
		// 	LEFT JOIN po_payment_received ON po_payment_received.lead_opportunity_wise_po_id=t2.id 
		// 	LEFT JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id
		// 	WHERE t1.pro_forma_no!='' AND om_po_pi_wise_stage_tag.id IS NOT NULL GROUP BY t1.lead_opportunity_wise_po_id ORDER BY om_po_pi_wise_stage_tag.priority DESC,t2.id ASC";

		$result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
        $order_by_str = " ORDER BY om_po_pi_wise_stage_tag.priority DESC,t1.id DESC ";

		if($argument['stage_id']){
			$subsql .= " AND om_po_pi_wise_stage_tag.stage_id='".$argument['stage_id']."'";
		}

		if($argument['string_search']){
			$priority = (strtolower($argument['string_search'])=='normal')?'1':'2';
			$subsql .= " AND (t1.lead_opportunity_wise_po_id='".$argument['string_search']."' || om_stage.name LIKE '%".$argument['string_search']."%' || cus.company_name LIKE '%".$argument['string_search']."%' || countries.name LIKE '%".$argument['string_search']."%' || states.name LIKE '%".$argument['string_search']."%' || cities.name LIKE '%".$argument['string_search']."%' || om_po_pi_wise_stage_tag.priority = '".$priority."')";
		}
		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.expected_delivery_date,
			t2.po_date,
			t2.is_cancel,
			t2.cancelled_date,
			t2.cancelled_by,
			cus.contact_person AS cus_contact_person,
			cus.company_name AS cus_company_name,	
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,			
			om_po_pi_wise_stage_tag.id AS om_po_pi_wise_stage_tag_id,
			om_po_pi_wise_stage_tag.stage_id AS pi_stage_id,
			om_po_pi_wise_stage_tag.po_pi_split_id AS split_id,
			om_po_pi_wise_stage_tag.priority AS pi_priority,
			om_po_pi_wise_stage_tag.is_lock,
			om_stage.name AS stage_name,
			GROUP_CONCAT(om_stage_form_submitted.form_id) AS submitted_form_id
			FROM po_pro_forma_invoice AS t1 
			INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
			INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  			
			INNER JOIN lead ON t3.lead_id=lead.id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 			
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city  			 
			LEFT JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id 
			LEFT JOIN om_stage ON om_stage.id=om_po_pi_wise_stage_tag.stage_id 
			LEFT JOIN om_stage_form_submitted ON om_stage_form_submitted.po_pi_id=t1.id AND  
            om_stage_form_submitted.pro_forma_invoice_split_id = (CASE WHEN om_po_pi_wise_stage_tag.po_pi_split_id IS NOT NULL THEN om_po_pi_wise_stage_tag.po_pi_split_id ELSE '' END)   
			WHERE t1.pro_forma_no!='' AND t2.is_cancel='N' AND om_po_pi_wise_stage_tag.id IS NOT NULL $subsql GROUP BY om_po_pi_wise_stage_tag.id $order_by_str LIMIT $start,$limit";
		// echo $sql; die();
		
		$result=$this->client_db->query($sql);				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function AddProformaSplit($data)
	{
		if($this->client_db->insert('po_pro_forma_invoice_split',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function AddProformaProductSplit($data)
	{
		if($this->client_db->insert('po_pro_forma_invoice_product_split',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
		
	}

	public function GetSplitProFormaInvoiceProductList($po_pro_forma_invoice_split_id)
	{		
		$sql="SELECT 
				t1.id,
				t1.po_pro_forma_invoice_id,
				t1.product_id,
				t1.product_name,
				t1.product_sku,
				t1.unit,
				t1.unit_type,
				t1.quantity,
				t1.price,
				t1.discount,
				t1.is_discount_p_or_a,
				t1.gst,
				t1.sort,
				t1_split.quantity AS split_quantity,
				t2.description,
				t3.file_name AS image,
				t4.file_name AS brochure  
				FROM po_pro_forma_invoice_product_split AS t1_split 
				INNER JOIN po_pro_forma_invoice_product AS t1 ON t1_split.po_pro_forma_invoice_product_id=t1.id
				LEFT JOIN po_pro_forma_invoice AS pro_forma_inv ON t1.po_pro_forma_invoice_id=pro_forma_inv.id
				LEFT JOIN product_varient AS t2 ON t1.product_id=t2.id
				LEFT JOIN product_images AS t3 ON t1.product_id=t3.varient_id
				LEFT JOIN product_pdf AS t4 ON t1.product_id=t4.varient_id
				WHERE t1_split.po_pro_forma_invoice_split_id='".$po_pro_forma_invoice_split_id."' GROUP BY t1.id
				ORDER BY t1.sort";	
		// echo $sql; die();	
		$result=$this->client_db->query($sql);	
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}		
	}

	public function delete_ExistingSplitProFormaInvoiceInfo($pfi_split_id='')
	{
		if($pfi_split_id!=''){
			$this->client_db->where('po_pro_forma_invoice_split_id', $pfi_split_id);
			$this->client_db->delete('po_pro_forma_invoice_product_split');

			$this->client_db->where('id', $pfi_split_id);
			$this->client_db->delete('po_pro_forma_invoice_split');
		}		
		return true;
	}

	public function get_om_po_pi_wise_stage_tag_by_pi($pi_id)
	{
		$sql="SELECT t1.id,
					t1.stage_id,
					t1.po_pi_id,
					t1.po_pi_split_id,
					t1.priority,
					t1.updated_by 
				FROM om_po_pi_wise_stage_tag AS t1 WHERE t1.po_pi_id='".$pi_id."'";
		$result=$this->client_db->query($sql);				
		if($result){
			if($result->num_rows()){
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

	public function delete_om_po_pi_wise_stage_tag_by_pi($pi_id='')
	{
		if($pi_id!=''){
			$this->client_db->where('po_pi_id', $pi_id);
			$this->client_db->delete('om_po_pi_wise_stage_tag');
			if(!$this->client_db->affected_rows()){
				return false;
			}else{
				return true;
			}	
		}
		else{
			return false;
		}		
		
	}

	public function delete_om_po_pi_wise_stage_tag_by_pi_split_id($po_pi_split_id='')
	{
		if($po_pi_split_id!=''){
			$this->client_db->where('po_pi_split_id', $po_pi_split_id);
			$this->client_db->delete('om_po_pi_wise_stage_tag');

			$this->client_db->where('po_pi_split_id', $po_pi_split_id);
			$this->client_db->delete('om_po_pi_wise_stage_tag_log');
		}		
		return true;
	}

	public function get_stage_wise_assigned_user()
	{
		$sql="SELECT t1.stage_id,GROUP_CONCAT(t1.user_id) AS user_ids
				FROM om_stage_wise_assigned_user AS t1 GROUP BY t1.stage_id";
		$result=$this->client_db->query($sql);				
		if($result){
			$return=array();
			if($result->num_rows()){
				foreach($result->result() AS $row){
					$return[$row->stage_id]=$row->user_ids;
				}
				return $return;
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
	}

	public function get_existing_priority_by_pfi($pfi)
	{
		$sql="SELECT priority FROM om_po_pi_wise_stage_tag WHERE po_pi_id='".$pfi."' LIMIT 1";
		$result=$this->client_db->query($sql);				
		if($result){
			$return=array();
			if($result->num_rows()){
				$row=$result->row();
				return $row->priority;
			}
			else{
				return '1';
			}
		}
		else{
			return '1';
		}
	}

	public function isStageExistInTag($stage_id='')
	{
		if($stage_id){
			$sql="SELECT t1.id
			FROM om_po_pi_wise_stage_tag AS t1 		
			WHERE t1.stage_id='".$stage_id."'";
			$result=$this->client_db->query($sql);			
			if($result){
				if($result->num_rows()){				
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
		else{
			return 'N';
		}		
	}

	public function GetActivePermissionLinkList()
	{
		$sql="SELECT 
		t1.id,
		t1.link_keyword,
		t1.link_display_name,
		t1.link_description,
		t1.sort_order,
		group_concat(t2.user_name ORDER BY t2.user_name) AS assigned_user_name_str
		FROM om_permission_link AS t1 
		LEFT JOIN 
		(
			SELECT a.om_permission_link_id,b.name AS user_name FROM om_permission_link_wise_assigned_user AS a INNER JOIN user AS b ON a.user_id=b.id
		) AS t2 ON t1.id=t2.om_permission_link_id 
		WHERE t1.is_active='Y' AND t1.is_deleted='N' GROUP BY t1.id ORDER BY t1.sort_order";
		$result=$this->client_db->query($sql);

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetUserWhoAreNotAssignedToPermissionLink($permission_link_id='')
	{
		if($permission_link_id)
		{
			$sql="SELECT 
			t1.id,
			t1.name
			FROM user AS t1 
			WHERE t1.status='0'  
			AND t1.id NOT IN (SELECT user_id FROM om_permission_link_wise_assigned_user WHERE om_permission_link_id='".$permission_link_id."') 
			GROUP BY t1.id";
			$result=$this->client_db->query($sql);				
			if($result){
				if($result->num_rows()){				
					return $result->result_array();
				}
				else{
					return array();
				}				
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
	}

	public function chk_duplicate_link_wise_assigned_user($permission_link_id=0,$user_id=0)
	{
		if($permission_link_id>0 && $user_id>0){
			$sql="SELECT t1.id
			FROM om_permission_link_wise_assigned_user AS t1 
			WHERE t1.om_permission_link_id='".$permission_link_id."' AND t1.user_id='".$user_id."'";
			$result=$this->client_db->query($sql);					
			if($result){
				if($result->num_rows()){
					return $result->row()->id;
				}
				else{
					return 0;
				}
			}
			else{
				return 0;
			}
		}
		else{
			return 0;
		}
		
	}
		
	

	function add_link_wise_assigned_user($data)
	{
		if($this->client_db->insert('om_permission_link_wise_assigned_user',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function GetUserWhoAreAssignedToPermissionLink($permission_link_id='')
	{
		if($permission_link_id)
		{
			$sql="SELECT 
			t1.id,
			t1.name
			FROM user AS t1 
			WHERE t1.status='0'  
			AND t1.id IN (SELECT user_id FROM om_permission_link_wise_assigned_user WHERE om_permission_link_id='".$permission_link_id."') 
			GROUP BY t1.id";
			$result=$this->client_db->query($sql);				
			if($result){
				if($result->num_rows()){				
					return $result->result_array();
				}
				else{
					return array();
				}				
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
	}

	public function delete_link_wise_assigned_user_by_linkIdAndUserId($permission_link_id='',$user_id='')
	{
		if($permission_link_id!='' && $user_id!=''){
			$this->client_db->where('om_permission_link_id', $permission_link_id);
			$this->client_db->where('user_id', $user_id);
			$this->client_db->delete('om_permission_link_wise_assigned_user');
		}		
		return true;
	}
}
?>