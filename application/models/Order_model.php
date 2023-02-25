<?php
class Order_model extends CI_Model
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
	
	function get_po_register_list_count($argument=NULL)
    {
        $subsql = ''; 
		$subsqlInner='';
        // ---------------------------------------
        // SEARCH VALUE 

		if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id
			FROM lead_opportunity_wise_po AS t1 
			INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
			INNER JOIN currency AS l_opp_currency ON t2.currency_type=l_opp_currency.id
			INNER JOIN lead ON t2.lead_id=lead.id 
			LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id 
			LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			INNER JOIN source ON source.id=lead.source_id 
			INNER JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			WHERE 1=1 $subsql ";

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
    
    function get_po_register_list($argument=NULL)
    {
       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
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

		// if($argument['filter_search_by_id']!='')
		// {
		// $subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
		// }

        if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_id,
			t1.file_path,
			t1.file_name,
			t1.cc_to_employee,
			t1.is_send_acknowledgement_to_client,
			t1.comments,
			t1.po_number,
			t1.po_date,
			t1.create_date,
			t1.is_cancel,
			t2.lead_id,
			t2.deal_value_as_per_purchase_order,
			lead.id AS l_id,
			lead.title AS l_title,
			lead.customer_id AS l_customer_id,
			lead.assigned_user_id AS l_assigned_user_id,
			lead.buying_requirement AS l_buying_requirement,
			lead.enquiry_date AS l_enquiry_date,
			lead.followup_date AS l_followup_date,
			lead.modify_date AS l_modify_date,
			lead.current_stage_id AS l_current_stage_id,			
			lead.current_stage AS l_current_stage,
			lead.current_status AS l_current_status,
			lead.is_hotstar AS l_is_hotstar,
			c_r_c.*,
			c_paying_info.*,
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
			source.name AS source_name,
			user.name AS assigned_user_name,
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,
			l_opp_currency.code AS lead_opp_currency_code,
			payment_term.*,
			po_pro_forma_invoice.id AS po_pro_forma_invoice_id,
			po_pro_forma_invoice.pro_forma_no,
			po_pro_forma_invoice.pro_forma_date,
			po_invoice.id AS invoice_id,
			po_invoice.invoice_no,
			po_invoice.invoice_date
			FROM lead_opportunity_wise_po AS t1 
			INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
			INNER JOIN currency AS l_opp_currency ON t2.currency_type=l_opp_currency.id
			INNER JOIN lead ON t2.lead_id=lead.id 
			LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id 
			LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			INNER JOIN source ON source.id=lead.source_id 
			INNER JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			LEFT JOIN 
			(
				SELECT 
				table1.lead_opportunity_wise_po_id AS lowp,
				table1.payment_type,
				table1.total_amount,
				SUM(table2.amount) AS total_payble_amount,
				SUM(if(table2.is_payment_received='Y',table2.amount,0)) AS payment_received
				FROM po_payment_terms AS table1 
				INNER JOIN po_payment_term_details AS table2 ON table1.id=table2.po_payment_term_id GROUP BY table2.po_payment_term_id

			) AS payment_term ON payment_term.lowp=t1.id 
			LEFT JOIN po_pro_forma_invoice ON po_pro_forma_invoice.lead_opportunity_wise_po_id=t1.id
			LEFT JOIN po_invoice ON po_invoice.lead_opportunity_wise_po_id=t1.id
			WHERE 1=1 $subsql 
			ORDER BY t1.id DESC 
			LIMIT $start,$limit";
		//echo $sql;die();
        $query = $this->client_db->query($sql,false);        
        // $last_query = $this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }

    function get_po_register_detail($lowp)
    {       
        $result = array();            
		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_id,
			t1.file_path,
			t1.file_name,
			t1.cc_to_employee,
			t1.is_send_acknowledgement_to_client,
			t1.comments,
			t1.po_number,
			t1.po_date,
			t1.po_tds_percentage,
			t1.po_tds_certificate,
			t1.create_date,
			t1.is_cancel,
			t1.cancelled_date,
			t1.cancelled_by,
			t1.cancelled_by_id,
			t2.lead_id,
			t2.currency_type,
			t2.deal_value_as_per_purchase_order,
			t2.deal_value,
			t2.currency_type AS po_currency_type_id,
			payment.*,
			payment_received.*,
			if(payment.po_pt_id IS NOT NULL, 'Y','N') AS is_payment_term_exist,			
			if(p_invoice.id IS NOT NULL, 'Y','N') AS is_p_invoice_exist,
			p_invoice.id AS proforma_id,
			p_invoice.proforma_type,
			p_invoice.po_custom_proforma,	
			p_invoice.pro_forma_no,
			p_invoice.pro_forma_date,
			p_invoice.expected_delivery_date AS pro_forma_expected_delivery_date,
			if(invoice.id IS NOT NULL, 'Y','N') AS is_invoice_exist,
			invoice.id AS invoice_id,
			invoice.invoice_type,
			invoice.po_custom_invoice,			
			invoice.invoice_no,
			invoice.invoice_date,
			lo_l.*,
			currency.code AS po_currency_type_code
			FROM lead_opportunity_wise_po AS t1 
			INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
			LEFT JOIN 
			(
				SELECT 
				T1.id AS po_pt_id,
				T1.lead_opportunity_wise_po_id AS lowpo,
				T1.payment_type,
				T1.total_amount,
				T1.created_at AS p_created_at,
				T1.updated_at AS p_updated_at,
				GROUP_CONCAT(CONCAT(T3.name,'#',T2.payment_mode_id,'#',T2.payment_date,'#',T2.amount,'#',T2.narration,'#',T2.created_at,'#',T2.updated_at,'#',T2.currency_type) ORDER BY T2.payment_date ) AS payment_log
				FROM po_payment_terms AS T1 
				INNER JOIN po_payment_term_details AS T2 ON T1.id=T2.po_payment_term_id 
				INNER JOIN po_payment_mode AS T3 ON T2.payment_mode_id=T3.id
				GROUP BY T2.po_payment_term_id 
				ORDER BY T2.payment_date
			)
			AS payment ON t1.id=payment.lowpo
			LEFT JOIN 
			(
				SELECT 
				T1.id AS po_pr_id,
				T1.lead_opportunity_wise_po_id AS pr_lowpo,
				GROUP_CONCAT(CONCAT(T1.received_date,'#',T1.currency_type,'#',T1.amount,'#',T1.payment_mode_id,T3.name,'#',T1.narration,'#',T1.created_at)) AS payment_received_log
				FROM po_payment_received AS T1 
				INNER JOIN po_payment_mode AS T3 ON T1.payment_mode_id=T3.id
				GROUP BY T1.lead_opportunity_wise_po_id 
				ORDER BY T1.received_date
			)
			AS payment_received ON t1.id=payment_received.pr_lowpo
			LEFT JOIN po_pro_forma_invoice AS p_invoice ON t1.id=p_invoice.lead_opportunity_wise_po_id 
			LEFT JOIN po_invoice AS invoice ON t1.id=invoice.lead_opportunity_wise_po_id 
			LEFT JOIN (
				SELECT lo.id AS lo_id,
				quotation.id AS q_id,
				quotation.quote_no AS q_quote_no,
				quotation.file_name AS q_file_name,
				quotation.is_extermal_quote AS q_is_extermal_quote,
				l.id AS lid,
				l.assigned_user_id,
				l.enquiry_date AS l_enquiry_date,
				c.id AS cust_id,
				c.assigned_user_id AS cust_assigned_user_id,
				c.country_id AS cust_country_id,
				c.state AS cust_state_id,
				c.city AS cust_city_id,				
				c.email AS cust_email,
				c.alt_email AS cust_alt_email,
				c.company_name AS cust_company_name,
				c.contact_person AS cust_contact_person,
				c.mobile_country_code AS cust_mobile_country_code,
				c.mobile AS cust_mobile,
				c.mobile_whatsapp_status AS cust_mobile_whatsapp_status,
				c.website AS cust_website,
				c.gst_number AS cust_gst_number,
				user.name AS cust_assigned_user,
				user.email AS cust_assigned_user_email,
				user.mobile AS cust_assigned_user_mobile,
				cities.name AS cust_city_name,
				states.name AS cust_state_name,
				countries.name AS cust_country_name
				 FROM lead_opportunity AS lo 
				 INNER JOIN lead AS l 
				 ON lo.lead_id=l.id 
				 INNER JOIN customer AS c ON c.id=l.customer_id 
				 INNER JOIN user ON user.id=c.assigned_user_id 
				 LEFT JOIN cities ON cities.id=c.city
				 LEFT JOIN states ON states.id=c.state 
				 LEFT JOIN countries ON countries.id=c.country_id 
				 LEFT JOIN quotation ON quotation.opportunity_id=lo.id
			) AS lo_l ON lo_l.lo_id=t1.lead_opportunity_id 
			LEFT JOIN currency ON currency.id=t2.currency_type
			WHERE t1.id='".$lowp."' GROUP BY t1.id";
		//echo $sql;die();
        $query = $this->client_db->query($sql,false);
        //$last_query = $this->client_db->last_query();die();
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
        
    }
    public function po_payment_method_list()
	{
		$sql="SELECT id,name FROM po_payment_mode ORDER BY name ASC";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}


	function CreatePoPaymentTerms($data)
	{		
		if($this->client_db->insert('po_payment_terms',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           	return $this->client_db->insert_id();
   		}
   		else
   		{
   			// echo $last_query = $this->client_db->last_query();die();
          return false;
   		}
	}

	function CreatePoPaymentTermDetails($data)
	{		
		if($this->client_db->insert('po_payment_term_details',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	function updatePoPaymentTerms($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('po_payment_terms',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function updatePoPaymentTermDetailByTermId($data,$tid)
	{
		$this->client_db->where('po_payment_term_id',$tid);
		if($this->client_db->update('po_payment_term_details',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function deletePoPaymentTermDetailByTermId($tid)
	{
		$this->client_db->where('po_payment_term_id', $tid);
    	if($this->client_db->delete('po_payment_term_details'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function chk_already_exist_lead_opp_wise($lowpo_id,$payment_method)
	{
		$sql="SELECT id			
			FROM po_payment_terms 
			WHERE lead_opportunity_wise_po_id='".$lowpo_id."' AND payment_type='".$payment_method."'";  

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
	
	function delete_po_payment_terms_lead_opp_wise($owp_id)
	{
		$sql = "DELETE 
    			t1,t2
    			FROM po_payment_terms t1
  				JOIN po_payment_term_details t2 ON t1.id = t2.po_payment_term_id
  				WHERE t1.lead_opportunity_wise_po_id = '".$owp_id."'";
		$this->client_db->query($sql, array($id));
	}

	function CreatePoProFormaInvoice($data)
	{		
		if($this->client_db->insert('po_pro_forma_invoice',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           	return $this->client_db->insert_id();
   		}
   		else
   		{
   			// echo $last_query = $this->client_db->last_query();die();
          return false;
   		}
	}
	function CreatePoProFormaInvoiceProduct($data)
	{		
		if($this->client_db->insert('po_pro_forma_invoice_product',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           	return $this->client_db->insert_id();
   		}
   		else
   		{
   			// echo $last_query = $this->client_db->last_query();die();
          return false;
   		}
	}

	function CreatePoProFormaInvoiceAdditionalCharges($data)
	{		
		if($this->client_db->insert('po_pro_forma_invoice_additional_charges',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           	return $this->client_db->insert_id();
   		}
   		else
   		{
   			// echo $last_query = $this->client_db->last_query();die();
          return false;
   		}
	}


	function CreateInvoice($data)
	{		
		if($this->client_db->insert('po_invoice',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           	return $this->client_db->insert_id();
   		}
   		else
   		{
   			// echo $last_query = $this->client_db->last_query();die();
          return false;
   		}
	}
	function CreateInvoiceProduct($data)
	{		
		if($this->client_db->insert('po_invoice_product',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           	return $this->client_db->insert_id();
   		}
   		else
   		{
   			// echo $last_query = $this->client_db->last_query();die();
          return false;
   		}
	}

	function CreateInvoiceAdditionalCharges($data)
	{		
		if($this->client_db->insert('po_invoice_additional_charges',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           	return $this->client_db->insert_id();
   		}
   		else
   		{
   			// echo $last_query = $this->client_db->last_query();die();
          return false;
   		}
	}

	public function GetProFormaInvoiceProductList($lowp)
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
				t2.description,
				t3.file_name AS image,
				t4.file_name AS brochure  FROM po_pro_forma_invoice_product AS t1
				LEFT JOIN po_pro_forma_invoice AS pro_forma_inv ON t1.po_pro_forma_invoice_id=pro_forma_inv.id
				LEFT JOIN product_varient AS t2 ON t1.product_id=t2.id
				LEFT JOIN product_images AS t3 ON t1.product_id=t3.varient_id
				LEFT JOIN product_pdf AS t4 ON t1.product_id=t4.varient_id
				WHERE pro_forma_inv.lead_opportunity_wise_po_id='".$lowp."' GROUP BY t1.id
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

	public function GetProFormaInvoiceProduct($id)
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
				t2.description,
				t3.file_name AS image,
				t4.file_name AS brochure  FROM po_pro_forma_invoice_product AS t1
				LEFT JOIN po_pro_forma_invoice AS pro_forma_inv ON t1.po_pro_forma_invoice_id=pro_forma_inv.id
				LEFT JOIN product_varient AS t2 ON t1.product_id=t2.id
				LEFT JOIN product_images AS t3 ON t1.product_id=t3.varient_id
				LEFT JOIN product_pdf AS t4 ON t1.product_id=t4.varient_id
				WHERE t1.id='".$id."'";	
		// echo $sql; die();	
		$result=$this->client_db->query($sql);	
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
		
	}

	public function GetProFormaInvoiceAdditionalCharges($lowp)
	{		
		$sql="SELECT 
			t1.id,
			t1.po_pro_forma_invoice_id,
			t1.additional_charge_id,
			t1.additional_charge_name,
			t1.price,
			t1.discount,
			t1.is_discount_p_or_a,
			t1.gst,
			t1.created_at,
			t1.sort 
			FROM po_pro_forma_invoice_additional_charges AS t1 
			INNER JOIN po_pro_forma_invoice AS pro_forma_inv ON t1.po_pro_forma_invoice_id=pro_forma_inv.id 
			WHERE pro_forma_inv.lead_opportunity_wise_po_id='".$lowp."' ORDER BY t1.sort";
			// echo $sql; die();
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function GetProFormaInvoiceAdditionalChargesRow($id)
	{		
		$sql="SELECT 
			t1.id,
			t1.po_pro_forma_invoice_id,
			t1.additional_charge_id,
			t1.additional_charge_name,
			t1.price,
			t1.discount,
			t1.is_discount_p_or_a,
			t1.gst,
			t1.created_at 
			FROM po_pro_forma_invoice_additional_charges AS t1 
			INNER JOIN po_pro_forma_invoice AS pro_forma_inv ON t1.po_pro_forma_invoice_id=pro_forma_inv.id 
			WHERE t1.id='".$id."'";
			// echo $sql; die();
		$result=$this->client_db->query($sql);
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
		
	}

	function get_po_pro_forma_invoice_detail_lowpo_wise($lowp)
	{
		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.proforma_type,
			t1.po_custom_proforma,
			t1.pro_forma_no,
			t1.pro_forma_date,
			t1.due_date,
			t1.expected_delivery_date,
			t1.bill_from,
			t1.bill_to,
			t1.ship_to,
			t1.terms_conditions,
			t1.additional_note,
			t1.bank_detail_1,
			t1.bank_detail_2,
			t1.is_digital_signature_checked,
			t1.name_of_authorised_signature,
			t1.thanks_and_regards,
			t1.currency_type,
			t1.created_at,
			t1.updated_at FROM po_pro_forma_invoice AS t1 WHERE t1.lead_opportunity_wise_po_id='".$lowp."'";
			// echo $sql;die();
			$result=$this->client_db->query($sql);			
			if($result){
				return $result->row();
			}
			else{
				return (object)array();
			}
	}

	function get_pro_forma_inv_mode($id)
	{
		$sql="SELECT pro_forma_no 
				FROM po_pro_forma_invoice WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		if($result){
			$row=$result->row();
			if($row->pro_forma_no)
			{
				return 'update';
			}
			else
			{
				return 'insert';
			}
		}
		
	}

	function get_pro_forma_invoice($id)
	{
		$sql="SELECT id,
				lead_opportunity_wise_po_id,
				proforma_type,
				po_custom_proforma,
				pro_forma_no,
				pro_forma_date,
				bill_from,
				bill_to,
				ship_to,
				terms_conditions,
				additional_note,
				bank_detail_1,
				bank_detail_2,
				is_digital_signature_checked,
				name_of_authorised_signature,
				thanks_and_regards,
				currency_type,
				total_pro_forma_inv_amount,
				created_at,
				updated_at 
				FROM po_pro_forma_invoice WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}	
	}

	function updatePoFormaInvoice($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('po_pro_forma_invoice',$data))
		{
			// echo  $this->client_db->last_query();die();
			return true;
		}
		else
		{
			// echo $this->client_db->last_query();die();
			return false;
		}
	}

	public function update_product($data_post,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('po_pro_forma_invoice_product',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function UpdateProFormaInvProdByProFormaInvId($data_post,$pro_forma_inv_id)
	{
		$this->client_db->where('po_pro_forma_invoice_id',$pro_forma_inv_id);
		if($this->client_db->update('po_pro_forma_invoice_product',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	public function DeleteProFormaInvoiceProduct($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('po_pro_forma_invoice_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function update_additional_charges($data_post,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('po_pro_forma_invoice_additional_charges',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function UpdateProFormaInvAdditionalChargesByProFormaInvId($data_post,$pro_forma_inv_id)
	{
		$this->client_db->where('po_pro_forma_invoice_id',$pro_forma_inv_id);
		if($this->client_db->update('po_pro_forma_invoice_additional_charges',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	public function DeleteProFormaInvoiceAdditionalCharges($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('po_pro_forma_invoice_additional_charges'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	function get_po_invoice_detail_lowpo_wise($lowp)
	{
		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.invoice_type,
			t1.po_custom_invoice,
			t1.invoice_no,
			t1.invoice_date,
			t1.due_date,
			t1.expected_delivery_date,
			t1.bill_from,
			t1.bill_to,
			t1.ship_to,
			t1.terms_conditions,
			t1.additional_note,
			t1.bank_detail_1,
			t1.bank_detail_2,
			t1.is_digital_signature_checked,
			t1.name_of_authorised_signature,
			t1.thanks_and_regards,
			t1.currency_type,
			t1.created_at,
			t1.updated_at FROM po_invoice AS t1 WHERE t1.lead_opportunity_wise_po_id='".$lowp."'";
			// echo $sql;die();
			$result=$this->client_db->query($sql);
			
			if($result){
				return $result->row();
			}
			else{
				return (object)array();
			}
	}

	public function GetInvoiceProductList($lowp)
	{		
		$sql="SELECT 
				t1.id,
				t1.po_invoice_id,
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
				t2.description,
				t3.file_name AS image,
				t4.file_name AS brochure  FROM po_invoice_product AS t1
				LEFT JOIN po_invoice AS inv ON t1.po_invoice_id=inv.id
				LEFT JOIN product_varient AS t2 ON t1.product_id=t2.id
				LEFT JOIN product_images AS t3 ON t1.product_id=t3.varient_id
				LEFT JOIN product_pdf AS t4 ON t1.product_id=t4.varient_id
				WHERE inv.lead_opportunity_wise_po_id='".$lowp."' GROUP BY t1.id
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

	public function GetInvoiceAdditionalCharges($lowp)
	{		
		$sql="SELECT 
			t1.id,
			t1.po_invoice_id,
			t1.additional_charge_id,
			t1.additional_charge_name,
			t1.price,
			t1.discount,
			t1.is_discount_p_or_a,
			t1.gst,
			t1.created_at,
			t1.sort 
			FROM po_invoice_additional_charges AS t1 
			INNER JOIN po_invoice AS inv ON t1.po_invoice_id=inv.id 
			WHERE inv.lead_opportunity_wise_po_id='".$lowp."' ORDER BY t1.sort";
			// echo $sql; die();
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}


	public function update_inv_product($data_post,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('po_invoice_product',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function GetInvoiceProduct($id)
	{		
		$sql="SELECT 
				t1.id,
				t1.po_invoice_id,
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
				t2.description,
				t3.file_name AS image,
				t4.file_name AS brochure  FROM po_invoice_product AS t1
				LEFT JOIN po_invoice AS inv ON t1.po_invoice_id=inv.id
				LEFT JOIN product_varient AS t2 ON t1.product_id=t2.id
				LEFT JOIN product_images AS t3 ON t1.product_id=t3.varient_id
				LEFT JOIN product_pdf AS t4 ON t1.product_id=t4.varient_id
				WHERE t1.id='".$id."'";	
		// echo $sql; die();	
		$result=$this->client_db->query($sql);	
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	function updateInvoice($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('po_invoice',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function DeleteInvoiceProduct($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('po_invoice_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function DeleteInvoiceProductByInvId($po_invoice_id)
	{
		$this->client_db->where('po_invoice_id',$po_invoice_id);
		if($this->client_db->delete('po_invoice_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function update_inv_additional_charges($data_post,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('po_invoice_additional_charges',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function GetInvoiceAdditionalChargesRow($id)
	{		
		$sql="SELECT 
			t1.id,
			t1.po_invoice_id,
			t1.additional_charge_id,
			t1.additional_charge_name,
			t1.price,
			t1.discount,
			t1.is_discount_p_or_a,
			t1.gst,
			t1.created_at 
			FROM po_invoice_additional_charges AS t1 
			INNER JOIN po_invoice AS inv ON t1.po_invoice_id=inv.id 
			WHERE t1.id='".$id."'";
			// echo $sql; die();
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	public function DeleteInvoiceAdditionalCharges($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('po_invoice_additional_charges'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function DeleteInvoiceAdditionalChargesByInvId($po_invoice_id)
	{
		$this->client_db->where('po_invoice_id',$po_invoice_id);
		if($this->client_db->delete('po_invoice_additional_charges'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function UpdateInvProdByProFormaInvId($data_post,$inv_id)
	{
		$this->client_db->where('po_invoice_id',$inv_id);
		if($this->client_db->update('po_invoice_product',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	public function UpdateInvAdditionalChargesByProFormaInvId($data_post,$inv_id)
	{
		$this->client_db->where('po_invoice_id',$inv_id);
		if($this->client_db->update('po_invoice_additional_charges',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	function get_inv_mode($id)
	{
		$sql="SELECT invoice_no 
				FROM po_invoice WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		if($result){
			$row=$result->row();
			if($row->invoice_no)
			{
				return 'update';
			}
			else
			{
				return 'insert';
			}
		}
		else{
			return '';
		}

		
	}

	function get_invoice($id)
	{
		$sql="SELECT id,
				lead_opportunity_wise_po_id,
				invoice_type,
				po_custom_invoice,
				invoice_no,
				invoice_date,
				bill_from,
				bill_to,
				ship_to,
				terms_conditions,
				currency_type,
				total_inv_amount,
				created_at,
				updated_at 
				FROM po_invoice WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}	
	}

	public function chkPoRelatedRecord($lowp)
	{
		$is_pro_forma_inv_exist='N';
		$is_inv_exist='N';
		// pro-forma-invoice
		$sql="SELECT id FROM po_pro_forma_invoice WHERE lead_opportunity_wise_po_id='".$lowp."'";
		$query = $this->client_db->query($sql,false); 
		if($query){
			if($query->num_rows() > 0) 
			{
				$is_pro_forma_inv_exist='Y';
			}
		}
		

        

        // invoice
		$sql="SELECT id FROM po_invoice WHERE lead_opportunity_wise_po_id='".$lowp."'";
		$query = $this->client_db->query($sql,false);     
		if($query){
			if($query->num_rows() > 0) 
			{
				$is_inv_exist='Y';
			}
		}

        

        return array(
    			'is_pro_forma_inv_exist'=>$is_pro_forma_inv_exist,
    			'is_inv_exist'=>$is_inv_exist
        			);

	}

	function get_invoice_management_list_count($argument=NULL)
    {
       
        $result = array();        
        $subsql = '';   
		$subsqlInner='';
        // ---------------------------------------
        // SEARCH VALUE 

		if($argument['filter_string_search']!='')
		{
			$subsql .= " AND  (t1.invoice_no='".$argument['filter_string_search']."' || cus.company_name LIKE '%".$argument['filter_string_search']."%' || cus.mobile LIKE '%".$argument['filter_string_search']."%' || cus.email LIKE '%".$argument['filter_string_search']."%')";
		}
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}

		if($argument['sort_by_fy_start_date']!='' && $argument['sort_by_fy_end_date']!='')
		{
			$subsql.=" AND (t1.invoice_date >='".$argument['sort_by_fy_start_date']."' AND t1.invoice_date <='".$argument['sort_by_fy_end_date']."')";
		}
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id 
			FROM po_invoice AS t1 
			INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
			INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  
			INNER JOIN currency AS l_opp_currency ON t3.currency_type=l_opp_currency.id
			INNER JOIN lead ON t3.lead_id=lead.id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			INNER JOIN source ON source.id=lead.source_id 
			INNER JOIN user ON user.id=lead.assigned_user_id WHERE t1.invoice_no!='' $subsql";
		// echo $sql;die();
        $query=$this->client_db->query($sql,false);
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
	function get_invoice_management_list($argument=NULL)
    {
       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
        //$order_by_str = " ORDER BY  lead.enquiry_date DESC,lead.id DESC ";
        $order_by_str = " ORDER BY t1.invoice_no DESC ";
        if($argument['filter_sort_by']!='')
        {
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 

		if($argument['filter_string_search']!='')
		{
			$subsql .= " AND  (t1.invoice_no='".$argument['filter_string_search']."' || cus.company_name LIKE '%".$argument['filter_string_search']."%' || cus.mobile LIKE '%".$argument['filter_string_search']."%' || cus.email LIKE '%".$argument['filter_string_search']."%')";
		}
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}

		if($argument['sort_by_fy_start_date']!='' && $argument['sort_by_fy_end_date']!='')
		{
			$subsql.=" AND (t1.invoice_date >='".$argument['sort_by_fy_start_date']."' AND t1.invoice_date <='".$argument['sort_by_fy_end_date']."')";
		}

        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.invoice_no,
			t1.invoice_date,
			t1.due_date,
			t1.expected_delivery_date,
			t1.bill_from,
			t1.bill_to,
			t1.ship_to,
			t1.terms_conditions,
			t1.additional_note,
			t1.bank_detail_1,
			t1.bank_detail_2,
			t1.is_digital_signature_checked,
			t1.name_of_authorised_signature,
			t1.thanks_and_regards,
			t1.currency_type,
			t1.total_inv_amount,
			t1.created_at,
			t1.updated_at,
			t2.is_cancel,
			t2.cancelled_date,
			t2.cancelled_by,
			t2.po_tds_percentage,
			t3.lead_id,
			t3.deal_value_as_per_purchase_order,
			lead.id AS l_id,
			lead.title AS l_title,
			lead.customer_id AS l_customer_id,
			lead.assigned_user_id AS l_assigned_user_id,
			lead.buying_requirement AS l_buying_requirement,
			lead.enquiry_date AS l_enquiry_date,
			lead.followup_date AS l_followup_date,
			lead.modify_date AS l_modify_date,
			lead.current_stage_id AS l_current_stage_id,			
			lead.current_stage AS l_current_stage,
			lead.current_status AS l_current_status,
			lead.is_hotstar AS l_is_hotstar,
			c_r_c.*,
			c_paying_info.*,
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
			source.name AS source_name,
			user.name AS assigned_user_name,
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,
			l_opp_currency.code AS lead_opp_currency_code,
			payment_term.*,
			po_pro_forma_invoice.id AS po_pro_forma_invoice_id,
			po_pro_forma_invoice.pro_forma_no,
			po_pro_forma_invoice.pro_forma_date,
			po_invoice.id AS invoice_id,
			po_invoice.invoice_no,
			po_invoice.invoice_date,
			SUM(po_payment_received.amount) AS payment_received
			FROM po_invoice AS t1 
			INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
			INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  
			INNER JOIN currency AS l_opp_currency ON t3.currency_type=l_opp_currency.id
			INNER JOIN lead ON t3.lead_id=lead.id 
			LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id 
			LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			INNER JOIN source ON source.id=lead.source_id 
			INNER JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			LEFT JOIN 
			(
				SELECT 
				table1.lead_opportunity_wise_po_id AS lowp,
				table1.payment_type,
				table1.total_amount,
				SUM(table2.amount) AS total_payble_amount
				FROM po_payment_terms AS table1 
				INNER JOIN po_payment_term_details AS table2 ON table1.id=table2.po_payment_term_id GROUP BY table2.po_payment_term_id

			) AS payment_term ON payment_term.lowp=t2.id 
			LEFT JOIN po_pro_forma_invoice ON po_pro_forma_invoice.lead_opportunity_wise_po_id=t2.id
			LEFT JOIN po_invoice ON po_invoice.lead_opportunity_wise_po_id=t2.id 
			LEFT JOIN po_payment_received ON po_payment_received.lead_opportunity_wise_po_id=t2.id
			WHERE t1.invoice_no!='' $subsql GROUP BY t1.lead_opportunity_wise_po_id  
			 $order_by_str 
			LIMIT $start,$limit";
		// echo $sql;die();
        $query=$this->client_db->query($sql,false);
        // $last_query=$this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }

    function get_payment_followup_list_trans_count($argument=NULL)
    {
       
        $result = array();        
        $subsql = '';   
		$subsqlInner='';
        // ---------------------------------------
        // SEARCH VALUE 

		// if($argument['filter_search_by_id']!='')
		// {
		// $subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
		// }

        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id
			FROM po_payment_terms AS t1
			INNER JOIN po_payment_term_details AS t2 ON t1.id=t2.po_payment_term_id  
			INNER JOIN lead_opportunity_wise_po AS t3 ON t1.lead_opportunity_wise_po_id=t3.id
			WHERE 1=1 $subsql 
			GROUP BY t2.id";
		// echo $sql;die();
        $query=$this->client_db->query($sql,false);
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
	function get_payment_followup_trans_list($argument=NULL)
    {
       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
        //$order_by_str = " ORDER BY  lead.enquiry_date DESC,lead.id DESC ";
        $order_by_str = " ORDER BY t1.lead_opportunity_wise_po_id,t2.payment_date DESC ";
        if($argument['filter_sort_by']!='')
        {
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 

		// if($argument['filter_search_by_id']!='')
		// {
		// $subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
		// }

        // SEARCH VALUE
        // ---------------------------------------


		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.payment_type,
			t1.total_amount,
			t1.created_at,
			t1.updated_at,
			t2.po_payment_term_id,
			t2.payment_mode_id,
			t2.payment_date,
			t2.currency_type,
			t2.amount,
			t2.payment_received,
			t2.balance_payment,
			t2.narration,
			t2.is_payment_received,
			t2.payment_received_datetime,
			t3.is_cancel,
			t3.cancelled_date,
			t3.cancelled_by,
			t4.lead_id,
			t4.deal_value_as_per_purchase_order,
			lead.id AS l_id,
			lead.title AS l_title,
			lead.customer_id AS l_customer_id,
			lead.assigned_user_id AS l_assigned_user_id,
			lead.buying_requirement AS l_buying_requirement,
			lead.enquiry_date AS l_enquiry_date,
			lead.followup_date AS l_followup_date,
			lead.modify_date AS l_modify_date,
			lead.current_stage_id AS l_current_stage_id,			
			lead.current_stage AS l_current_stage,
			lead.current_status AS l_current_status,
			lead.is_hotstar AS l_is_hotstar,
			c_r_c.*,
			c_paying_info.*,
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
			source.name AS source_name,
			user.name AS assigned_user_name,
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,
			l_opp_currency.code AS lead_opp_currency_code,
			po_pro_forma_invoice.id AS po_pro_forma_invoice_id,
			po_pro_forma_invoice.pro_forma_no,
			po_pro_forma_invoice.pro_forma_date,
			po_invoice.id AS invoice_id,
			po_invoice.invoice_no,
			po_invoice.invoice_date
			FROM po_payment_terms AS t1
			INNER JOIN po_payment_term_details AS t2 ON t1.id=t2.po_payment_term_id  
			INNER JOIN lead_opportunity_wise_po AS t3 ON t1.lead_opportunity_wise_po_id=t3.id 			
			INNER JOIN lead_opportunity AS t4 ON t3.lead_opportunity_id=t4.id  
			INNER JOIN currency AS l_opp_currency ON t4.currency_type=l_opp_currency.id
			INNER JOIN lead ON t4.lead_id=lead.id 
			LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id 
			LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			INNER JOIN source ON source.id=lead.source_id 
			INNER JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			LEFT JOIN po_pro_forma_invoice ON po_pro_forma_invoice.lead_opportunity_wise_po_id=t3.id
			LEFT JOIN po_invoice ON po_invoice.lead_opportunity_wise_po_id=t3.id 
			LEFT JOIN po_payment_received ON po_payment_received.lead_opportunity_wise_po_id=t3.id
			WHERE 1=1 $subsql 
			GROUP BY t2.id 
			$order_by_str 
			LIMIT $start,$limit";
		// echo $sql;die();
        $query=$this->client_db->query($sql,false);
        // $last_query=$this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }

    function get_payment_followup_group_list_count($argument=NULL)
    {
       
        $result = array();
        $subsql = '';   
		$subsqlInner='';
        //$order_by_str = " ORDER BY  lead.enquiry_date DESC,lead.id DESC ";
      

        // ---------------------------------------
        // SEARCH VALUE 

		if($argument['filter_string_search']!='')
		{
			$subsql .= " AND  (t1.lead_opportunity_wise_po_id = '".$argument['filter_string_search']."' || cus.company_name LIKE '%".$argument['filter_string_search']."%' || cus.mobile LIKE '%".$argument['filter_string_search']."%' || cus.email LIKE '%".$argument['filter_string_search']."%')";
		}
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}
        // SEARCH VALUE
        // ---------------------------------------


		$sql="SELECT 
			t1.id,
			t1.total_amount,
			t1.total_payment_received 
			FROM po_payment_terms AS t1
			INNER JOIN 
			(
				SELECT 
				po_payment_term_id,
				payment_mode_id,
				MAX(payment_date) AS payment_date,
				currency_type,
				SUM(amount) AS amount,
				MAX(narration) as narration,
				is_payment_received,
				payment_received_datetime
			 FROM po_payment_term_details 
			 GROUP BY po_payment_term_id
			) AS t2 ON t1.id=t2.po_payment_term_id  
			INNER JOIN lead_opportunity_wise_po AS t3 ON t1.lead_opportunity_wise_po_id=t3.id
			INNER JOIN lead_opportunity AS t4 ON t3.lead_opportunity_id=t4.id  
			INNER JOIN currency AS l_opp_currency ON t4.currency_type=l_opp_currency.id
			INNER JOIN lead ON t4.lead_id=lead.id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			LEFT JOIN po_payment_received ON po_payment_received.lead_opportunity_wise_po_id=t3.id
			WHERE 1=1 $subsql GROUP BY t1.id HAVING (t1.total_amount-t1.total_payment_received)>0";
		// echo $sql;die();
        $query=$this->client_db->query($sql,false);
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

    function get_payment_followup_group_list($argument=NULL)
    {
       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
        //$order_by_str = " ORDER BY  lead.enquiry_date DESC,lead.id DESC ";
        $order_by_str = " ORDER BY t2.payment_date ASC";
        if($argument['filter_sort_by']!='')
        {
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 

		if($argument['filter_string_search']!='')
		{
			$subsql .= " AND  (t1.lead_opportunity_wise_po_id = '".$argument['filter_string_search']."' || cus.company_name LIKE '%".$argument['filter_string_search']."%' || cus.mobile LIKE '%".$argument['filter_string_search']."%' || cus.email LIKE '%".$argument['filter_string_search']."%')";
		}
        if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}
        // SEARCH VALUE
        // ---------------------------------------


		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.payment_type,
			t1.total_amount,
			t1.total_payment_received,
			t1.total_balance_payment,
			t1.created_at,
			t1.updated_at,
			t2.po_payment_term_id,
			t2.payment_mode_id,
			t2.payment_date,
			t2.currency_type,
			t2.amount,
			t2.narration,
			t2.is_payment_received,
			t2.payment_received_datetime,
			t3.is_cancel,
			t3.cancelled_date,
			t3.cancelled_by,
			t4.lead_id,
			t4.deal_value_as_per_purchase_order,
			lead.id AS l_id,
			lead.title AS l_title,
			lead.customer_id AS l_customer_id,
			lead.assigned_user_id AS l_assigned_user_id,
			lead.buying_requirement AS l_buying_requirement,
			lead.enquiry_date AS l_enquiry_date,
			lead.followup_date AS l_followup_date,
			lead.modify_date AS l_modify_date,
			lead.current_stage_id AS l_current_stage_id,
			lead.current_stage AS l_current_stage,
			lead.current_status AS l_current_status,
			lead.is_hotstar AS l_is_hotstar,
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
			l_opp_currency.code AS lead_opp_currency_code,
			SUM(po_payment_received.amount) AS payment_received
			FROM po_payment_terms AS t1
			INNER JOIN 
			(
				SELECT 
				po_payment_term_id,
				payment_mode_id,
				MAX(payment_date) AS payment_date,
				currency_type,
				SUM(amount) AS amount,
				MAX(narration) as narration,
				is_payment_received,
				payment_received_datetime
			 FROM po_payment_term_details 
			 GROUP BY po_payment_term_id
			) AS t2 ON t1.id=t2.po_payment_term_id  
			INNER JOIN lead_opportunity_wise_po AS t3 ON t1.lead_opportunity_wise_po_id=t3.id
			INNER JOIN lead_opportunity AS t4 ON t3.lead_opportunity_id=t4.id  
			INNER JOIN currency AS l_opp_currency ON t4.currency_type=l_opp_currency.id
			INNER JOIN lead ON t4.lead_id=lead.id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			LEFT JOIN po_payment_received ON po_payment_received.lead_opportunity_wise_po_id=t3.id
			WHERE 1=1 $subsql 
			GROUP BY t1.id HAVING (t1.total_amount-t1.total_payment_received)>0 $order_by_str LIMIT $start,$limit ";
		// echo $sql;die();
        $query=$this->client_db->query($sql,false);
        // $last_query=$this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }

    function add_payment_received($data)
	{		
		if($this->client_db->insert('po_payment_received',$data))
   		{
   			// echo $last_query = $this->client_db->last_query();die();
           	return $this->client_db->insert_id();
   		}
   		else
   		{
   			// echo $last_query = $this->client_db->last_query();die();
          return false;
   		}
	}
	function get_payment_received($lowp)
    {
		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.received_date,
			t1.currency_type,
			t1.amount,
			t1.payment_mode_id,
			t1.narration,
			t1.created_at,
			t2.name AS payment_mode
			FROM po_payment_received AS t1 
			INNER JOIN po_payment_mode AS t2 ON t1.payment_mode_id=t2.id
			WHERE t1.lead_opportunity_wise_po_id='".$lowp."' 
			ORDER BY t1.received_date";
		// echo $sql;die();
        $query=$this->client_db->query($sql,false);
        // $last_query=$this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }

    public function del_payment_received($id)
	{
		$this->client_db->where('id', $id);
    	if($this->client_db->delete('po_payment_received'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function updateLeadOppWisePo($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('lead_opportunity_wise_po',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function GetLastProformaNo($arg)
	{
		$fy_start_date=$arg['fy_start_date'];
		$fy_end_date=$arg['fy_end_date'];
		$sql="SELECT MAX(pro_forma_no) AS max_no FROM po_pro_forma_invoice WHERE pro_forma_no IS NOT NULL AND pro_forma_date BETWEEN CAST('$fy_start_date' AS DATE) AND CAST('$fy_end_date' AS DATE)";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row()->max_no;
		}
		else{
			return 0;
		}
	}

	public function GetLastInvoiceNo($arg)
	{
		$fy_start_date=$arg['fy_start_date'];
		$fy_end_date=$arg['fy_end_date'];
		$sql="SELECT MAX(invoice_no) AS max_no FROM po_invoice WHERE invoice_no IS NOT NULL AND invoice_date BETWEEN CAST('$fy_start_date' AS DATE) AND CAST('$fy_end_date' AS DATE)";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row()->max_no;
		}
		else{
			return 0;
		}

	}

	function get_po_proforma_detail_lowpo_wise($lowp)
	{
		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.proforma_type,
			t1.po_custom_proforma,
			t1.pro_forma_no,
			t1.pro_forma_date,
			t1.due_date,
			t1.expected_delivery_date,
			t1.bill_from,
			t1.bill_to,
			t1.ship_to,
			t1.terms_conditions,
			t1.additional_note,
			t1.bank_detail_1,
			t1.bank_detail_2,
			t1.is_digital_signature_checked,
			t1.name_of_authorised_signature,
			t1.thanks_and_regards,
			t1.currency_type,
			t1.total_pro_forma_inv_amount,
			t1.created_at,
			t1.updated_at FROM po_pro_forma_invoice AS t1 WHERE t1.lead_opportunity_wise_po_id='".$lowp."'";
			// echo $sql;die();
			$result=$this->client_db->query($sql);
			
			if($result){
				return $result->row();
			}
			else{
				return (object)array();
			}
	}

	public function get_payment_terms_by_lowp($lowp)
	{

		$sql="SELECT 
		t1.id AS t_id,
		t1.lead_opportunity_wise_po_id,
		t1.payment_type,
		t1.total_amount,
		t1.total_payment_received,
		t1.total_balance_payment,
		t2.id AS td_id,
		t2.amount,
		t2.payment_received,
		t2.balance_payment,
		t2.payment_date
		FROM po_payment_terms AS t1 
		INNER JOIN po_payment_term_details AS t2 
		ON t1.id=t2.po_payment_term_id 
		WHERE t1.lead_opportunity_wise_po_id='".$lowp."' 
		ORDER BY t2.payment_date";
		$query=$this->client_db->query($sql,false);
        // echo $last_query=$this->client_db->last_query();
        
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_total_amount_received_by_lowp($lowp)
	{
		$sql="SELECT 
		SUM(t1.amount) AS total_amount_received
		FROM po_payment_received AS t1 
		WHERE t1.lead_opportunity_wise_po_id='".$lowp."' 
		ORDER BY t1.lead_opportunity_wise_po_id";
		$query=$this->client_db->query($sql,false);
        // echo $last_query=$this->client_db->last_query();die();
       
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
	}

	function updatePoPaymentTermDetailById($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('po_payment_term_details',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function get_payment_followup_split_list($argument=NULL)
    {
       
        $result = array();
        $pt_id=$argument['pt_id'];
        $subsql = '';   
		$subsqlInner='';
        //$order_by_str = " ORDER BY  lead.enquiry_date DESC,lead.id DESC ";
        $order_by_str = " ORDER BY t2.lead_opportunity_wise_po_id,t1.payment_date";
        if($argument['filter_sort_by']!='')
        {
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 

		// if($argument['filter_search_by_id']!='')
		// {
		// $subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
		// }

        // SEARCH VALUE
        // ---------------------------------------


		$sql="SELECT 
			t1.po_payment_term_id,
			t1.payment_mode_id,
			t1.payment_date,
			t1.currency_type,
			t1.amount,
			t1.payment_received,
			t1.balance_payment,
			t1.narration,
			t1.is_payment_received,
			t1.payment_received_datetime,			
			t2.lead_opportunity_wise_po_id,
			t2.payment_type,
			t2.total_amount,
			t2.created_at,
			t2.updated_at,			
			t3.is_cancel,
			t3.cancelled_date,
			t3.cancelled_by,
			t4.lead_id,
			t4.deal_value_as_per_purchase_order,
			lead.id AS l_id,
			lead.title AS l_title,
			lead.customer_id AS l_customer_id,
			lead.assigned_user_id AS l_assigned_user_id,
			lead.buying_requirement AS l_buying_requirement,
			lead.enquiry_date AS l_enquiry_date,
			lead.followup_date AS l_followup_date,
			lead.modify_date AS l_modify_date,
			lead.current_stage_id AS l_current_stage_id,			
			lead.current_stage AS l_current_stage,
			lead.current_status AS l_current_status,
			lead.is_hotstar AS l_is_hotstar,
			cus.id AS cus_id,
			cus.first_name AS cus_first_name,
			cus.last_name AS cus_last_name,
			cus.mobile_country_code AS cus_mobile_country_code,
			cus.mobile AS cus_mobile,
			cus.mobile_whatsapp_status AS cus_mobile_whatsapp_status,
			cus.email AS cus_email,
			cus.website AS cus_website,
			cus.contact_person AS cus_contact_person,
			cus.company_name AS cus_company_name 
			FROM po_payment_term_details AS t1
			INNER JOIN po_payment_terms AS t2 ON t1.po_payment_term_id=t2.id  
			INNER JOIN lead_opportunity_wise_po AS t3 ON t2.lead_opportunity_wise_po_id=t3.id 
			INNER JOIN lead_opportunity AS t4 ON t3.lead_opportunity_id=t4.id  
			INNER JOIN currency AS l_opp_currency ON t4.currency_type=l_opp_currency.id
			INNER JOIN lead ON t4.lead_id=lead.id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 			
			WHERE t1.po_payment_term_id=$pt_id $subsql 
			GROUP BY t1.id 
			$order_by_str ";
		// echo $sql;die();
        $query=$this->client_db->query($sql,false);
        $last_query=$this->client_db->last_query();
        
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
    }
	
	function get_proforma_invoice_management_list_count($argument=NULL)
    {
       
        $result = array();        
        $subsql = '';   
		$subsqlInner='';
        // ---------------------------------------
        // SEARCH VALUE 

		// if($argument['filter_search_by_id']!='')
		// {
		// $subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
		// }
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id 
			FROM po_pro_forma_invoice AS t1 
			INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
			INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  
			INNER JOIN currency AS l_opp_currency ON t3.currency_type=l_opp_currency.id
			INNER JOIN lead ON t3.lead_id=lead.id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			INNER JOIN source ON source.id=lead.source_id 
			INNER JOIN user ON user.id=lead.assigned_user_id 
			WHERE t1.pro_forma_no!='' $subsql GROUP BY t1.lead_opportunity_wise_po_id ";
		// echo $sql;die();
        $query=$this->client_db->query($sql,false);
        

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
	
	function get_proforma_invoice_management_list($argument=NULL)
    {
       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';
        //$order_by_str = " ORDER BY  lead.enquiry_date DESC,lead.id DESC ";
        $order_by_str = " ORDER BY t1.id DESC ";
        if($argument['filter_sort_by']!='')
        {
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 

		// if($argument['filter_search_by_id']!='')
		// {
		// $subsql .= " AND  lead.id='".$argument['filter_search_by_id']."'";
		// }
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND lead.assigned_user_id IN (".$argument['assigned_user'].")";
		}

        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.proforma_type,
			t1.po_custom_proforma,
			t1.pro_forma_no,
			t1.pro_forma_date,
			t1.due_date,
			t1.expected_delivery_date,
			t1.bill_from,
			t1.bill_to,
			t1.ship_to,
			t1.terms_conditions,
			t1.additional_note,
			t1.bank_detail_1,
			t1.bank_detail_2,
			t1.is_digital_signature_checked,
			t1.name_of_authorised_signature,
			t1.thanks_and_regards,
			t1.currency_type,
			t1.total_pro_forma_inv_amount,
			t1.created_at,
			t1.updated_at,
			t2.is_cancel,
			t2.cancelled_date,
			t2.cancelled_by,
			t2.po_tds_percentage,
			t3.lead_id,
			t3.deal_value_as_per_purchase_order,
			lead.id AS l_id,
			lead.title AS l_title,
			lead.customer_id AS l_customer_id,
			lead.assigned_user_id AS l_assigned_user_id,
			lead.buying_requirement AS l_buying_requirement,
			lead.enquiry_date AS l_enquiry_date,
			lead.followup_date AS l_followup_date,
			lead.modify_date AS l_modify_date,
			lead.current_stage_id AS l_current_stage_id,			
			lead.current_stage AS l_current_stage,
			lead.current_status AS l_current_status,
			lead.is_hotstar AS l_is_hotstar,
			c_r_c.*,
			c_paying_info.*,
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
			source.name AS source_name,
			user.name AS assigned_user_name,
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,
			l_opp_currency.code AS lead_opp_currency_code,
			payment_term.*,			
			po_invoice.id AS invoice_id,
			po_invoice.invoice_no,
			po_invoice.invoice_date,
			SUM(po_payment_received.amount) AS payment_received
			FROM po_pro_forma_invoice AS t1 
			INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
			INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  
			INNER JOIN currency AS l_opp_currency ON t3.currency_type=l_opp_currency.id
			INNER JOIN lead ON t3.lead_id=lead.id 
			LEFT JOIN (SELECT COUNT(customer_id) AS cust_repeat_count,customer_id AS cid FROM lead GROUP BY customer_id) AS c_r_c ON c_r_c.cid=lead.customer_id 
			LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS c_paying_info ON c_paying_info.custid=lead.customer_id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			INNER JOIN source ON source.id=lead.source_id 
			INNER JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city 
			LEFT JOIN 
			(
				SELECT 
				table1.lead_opportunity_wise_po_id AS lowp,
				table1.payment_type,
				table1.total_amount,
				SUM(table2.amount) AS total_payble_amount
				FROM po_payment_terms AS table1 
				INNER JOIN po_payment_term_details AS table2 ON table1.id=table2.po_payment_term_id GROUP BY table2.po_payment_term_id

			) AS payment_term ON payment_term.lowp=t2.id 			
			LEFT JOIN po_invoice ON po_invoice.lead_opportunity_wise_po_id=t2.id 
			LEFT JOIN po_payment_received ON po_payment_received.lead_opportunity_wise_po_id=t2.id
			WHERE t1.pro_forma_no!='' $subsql GROUP BY t1.lead_opportunity_wise_po_id  
			 $order_by_str
			LIMIT $start,$limit";
		// echo $sql;die();
        $query=$this->client_db->query($sql,false);
        // $last_query=$this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }
}
?>