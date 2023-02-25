<?php
class Api_model extends CI_Model
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

    // ------------------------------------
    // ---- USER LIST FOR AUTOSOL SYNC ----
    public function user_for_sync($client_info=array())
    {	
    	if($this->class_name=='autosol_sync')
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
		
		// Department
		$sql_d="SELECT 
				id,
				is_show_in_top_menu,
				parent_id,
				listing_position,
				category_name,
				category_image,
				description,
				seo_url,
				meta_title,
				meta_description,
				meta_keyword,
				sort_order,
				status,
				date_added,
				date_modified,
				is_deleted FROM category 
				WHERE 1=1 ORDER BY 1";
        $query_d=$this->client_db->query($sql_d,false);
        $department_list=$query_d->result_array();
        
        // -----------------
        // Designation
		$sql_dn="SELECT 
				id,
				name,
				created_at,
				updated_at,
				is_deleted FROM designation 
				WHERE 1=1";
        $query_dn=$this->client_db->query($sql_dn,false);
        $designation_list=$query_dn->result_array();

        // -----------------
        // functional_area
		$sql_dn="SELECT 
				id,
				name,
				created_at,
				updated_at,
				is_deleted FROM functional_area 
				WHERE 1=1";
        $query_dn=$this->client_db->query($sql_dn,false);
        $functional_area_list=$query_dn->result_array();


        // -----------------
        // cities
		/*$sql_c="SELECT 
				id,
				name,
				state_id FROM cities 
				WHERE 1=1";
        $query_c=$this->client_db->query($sql_c,false);
        $city_list=$query_c->result_array();*/
        $city_list=array();

        // -----------------
        // states
		/*$sql_s="SELECT 
				id,
				name,
				country_id FROM states 
				WHERE 1=1";
        $query_s=$this->client_db->query($sql_s,false);
        $state_list=$query_s->result_array();*/
        $state_list=array();
        

        // -----------------
        // countries
		/*$sql_c="SELECT 
				id,
				sortname,
				name,
				phonecode FROM countries 
				WHERE 1=1";
        $query_c=$this->client_db->query($sql_c,false);
        $country_list=$query_c->result_array();*/
		$country_list=array();
        // ------------------
        // User
		$sql="SELECT 
				id,
				department_id,
				manager_id,
				designation_id,
				functional_area_id,
				user_type,
				name,
				designation,
				email,
				password,
				mobile,
				company_name,
				address,
				city,
				state,
				country_id,
				website,
				company_industry_id,
				company_profile,
				date_of_birth,
				photo,
				personal_email,
				personal_mobile,
				gender,
				marital_status,
				marriage_anniversary,
				spouse_name,
				salary,
				salary_currency_code,
				pan,
				aadhar,
				joining_date,
				next_appraisal_date,
				sales_target_revenue,
				sales_target_revenue_type,
				sales_target_no_of_deal,
				target_setting,
				forget_key,
				create_date,
				modify_date,
				status,
				user_token 
				FROM user 
				WHERE 1=1 ORDER BY 1";
        $query=$this->client_db->query($sql,false);
        $user_list=array();
        $user_list=$query->result_array();
        
        return array(
		'department_list'=>$department_list,
		'designation_list'=>$designation_list,
		'functional_area_list'=>$functional_area_list,
		'city_list'=>$city_list,
		'state_list'=>$state_list,
		'country_list'=>$country_list,
		'user_list'=>$user_list
		);
    }
    // ---- USER LIST FOR AUTOSOL SYNC ----
    // ------------------------------------

    // ------------------------------------
    // -- CUSTOMER LIST FOR AUTOSOL SYNC --
    public function get_customer($client_info=array())
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
				customer.contact_person,
				customer.designation,
				customer.email,
				customer.alt_email,
				customer.mobile_country_code,
				customer.mobile,				
				customer.alt_mobile_country_code,
				customer.alt_mobile,				
				customer.landline_number,				
				customer.office_phone,
				customer.website,
				customer.company_name,
				customer.address,								
				customer.zip,
				customer.gst_number,
				customer.create_date,
				customer.short_description,	
				customer.is_blacklist,								
				countries.name AS country,
				cities.name AS city,
				states.name AS state,
				source.name AS source						
				FROM customer 
				LEFT JOIN countries ON countries.id=customer.country_id 
				LEFT JOIN states ON states.id=customer.state 
				LEFT JOIN cities ON cities.id=customer.city 
				LEFT JOIN source ON source.id=customer.source_id
				LEFT JOIN 
				(
					SELECT 
					lead.customer_id AS custid,
					IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer 
					FROM lead 
					LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id 
					GROUP BY lead.customer_id
				) AS c_paying_info 
				ON c_paying_info.custid=customer.id
				WHERE c_paying_info.is_paying_customer='Y' ORDER BY 1";
        $query=$this->client_db->query($sql,false);
		if($query){
			$customer_list=$query->result_array();  
			$customer_list_count=$query->num_rows();
			return array(
						"CODE"=> 200,
						"STATUS"=> "SUCCESS",
						"MESSAGE"=> "",
						"TOTAL_RECORDS"=> $customer_list_count,
						'RESPONSE'=>$customer_list
						);
		}
		else{
			return array(
						"CODE"=> 200,
						"STATUS"=> "SUCCESS",
						"MESSAGE"=> "",
						"TOTAL_RECORDS"=>0,
						'RESPONSE'=>''
						);
		}
        
    }
    // ---- CUSTOMER LIST FOR AUTOSOL SYNC ----
    // ----------------------------------------

    // ------------------------------------
    // ---- PRODUCT LIST FOR AUTOSOL SYNC ----
    public function product_for_sync($client_info=array())
    {	
    	if($this->class_name=='autosol_sync')
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
		
		// category
		$sql_d="SELECT 
				id,
				is_show_in_top_menu,
				parent_id,
				listing_position,
				category_name,
				category_image,
				description,
				seo_url,
				meta_title,
				meta_description,
				meta_keyword,
				sort_order,
				status,
				date_added,
				date_modified,
				is_deleted 
				FROM category 
				WHERE 1=1 ORDER BY 1";
        $query_d=$this->client_db->query($sql_d,false);
        $category_list=$query_d->result_array();
        
        // -----------------
        // currency
		$sql_1="SELECT 
				id,
				name,
				code 
				FROM currency 
				WHERE 1=1";
        $query_1=$this->client_db->query($sql_1,false);
        $currency_list=$query_1->result_array();

        // -----------------
        // unit_type
		$sql_2="SELECT 
				id,
				type_name 
				FROM unit_type 
				WHERE 1=1";
        $query_2=$this->client_db->query($sql_2,false);
        $unit_type_list=$query_2->result_array();


        // -----------------
        // unit_type
		$sql_3="SELECT 
				id,
				varient_id,
				file_name 
				FROM product_images 
				WHERE 1=1";
        $query_3=$this->client_db->query($sql_3,false);
        $product_image_list=$query_3->result_array();
        		
        // ------------------
        // product_varient
		$sql="SELECT 
				id,
				parent_id,
				group_id,
				cate_id,
				name,
				currency_type,
				unit_type,
				description,
				long_description,
				code,
				attachment,
				price,
				unit,
				minimum_order_quantity,
				status,
				disabled_reason,
				disabled_reason_text,
				gst_percentage,
				hsn_code,
				youtube_video,
				product_available_for,
				product_added_by,
				product_last_modified_by,
				date_modified,
				date_added,
				is_deleted 
				FROM product_varient 
				WHERE 1=1 ORDER BY 1";
        $query=$this->client_db->query($sql,false);
        $product_list=$query->result_array();
        
        return array(
			'category_list'=>$category_list,
			'currency_list'=>$currency_list,
			'unit_type_list'=>$unit_type_list,
			'product_list'=>$product_list,
			'product_image_list'=>$product_image_list
		);
    }
    // ---- PRODUCT LIST FOR AUTOSOL SYNC ----
    // ------------------------------------

    // ------------------------------------
    // -- CUSTOMER LIST FOR AUTOSOL SYNC --
    public function get_po($client_info=array())
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

        // lead_opportunity
        $sql_q_1="SELECT 
				t2.id,
				t2.lead_id,
				t2.source_id,
				t2.opportunity_title,
				t2.deal_value,
				t2.deal_value_as_per_purchase_order,
				t2.currency_type,
				t2.create_date,
				t2.modify_date,
				t2.status,
				t2.is_active,
				t2.is_deleted  
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id  				   
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t2.id ASC";
        $query_q_1=$this->client_db->query($sql_q_1,false);
        $lead_opportunity_list=$query_q_1->result_array();

        // lead_opportunity
        $sql_q_2="SELECT 
				t.id,
				t.opportunity_id,
				t.customer_id,
				t.quote_date,
				t.quote_no,
				t.quote_valid_until,
				t.is_extermal_quote,
				t.file_name,
				t.currency_type,
				t.is_product_image_show_in_quotation,
				t.is_product_brochure_attached_in_quotation,
				t.is_company_brochure_attached_in_quotation,
				t.is_quotation_bank_details1_send,
				t.is_quotation_bank_details2_send,
				t.is_gst_number_show_in_quotation,
				t.letter_to,
				t.letter_subject,
				t.letter_body_text,
				t.letter_footer_text,
				t.letter_terms_and_conditions,
				t.shipping_terms,
				t.payment_terms,
				t.letter_thanks_and_regards,
				t.is_digital_signature_checked,
				t.name_of_authorised_signature,
				t.create_date,
				t.modify_date   
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id  
				INNER JOIN quotation AS t ON t.opportunity_id=t2.id   
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t.id ASC";
        $query_q_2=$this->client_db->query($sql_q_2,false);
        $quotation_list=$query_q_2->result_array();

        // opportunity_product
        $sql_q_3="SELECT 
				t.id,
				t.opportunity_id,
				t.product_id,
				t.unit,
				t.unit_type,
				t.quantity,
				t.price,
				t.currency_type,
				t.discount,
				t.gst,
				t.create_date,
				t.status   
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id  
				INNER JOIN opportunity_product AS t ON t.opportunity_id=t2.id   
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t.id ASC";
        $query_q_3=$this->client_db->query($sql_q_3,false);
        $opportunity_product_list=$query_q_3->result_array();


        

		// ---------------------------
		// PO
        // lead_opportunity
        $sql_po_1="SELECT 
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
				t1.cancelled_by_id    
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id 
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t1.id ASC";
        $query_po_1=$this->client_db->query($sql_po_1,false);
        $lead_opportunity_wise_po_list=$query_po_1->result_array();

        $sql_po_2="SELECT 
				pi.id,
				pi.lead_opportunity_wise_po_id,
				pi.proforma_type,
				pi.po_custom_proforma,
				pi.pro_forma_no,
				pi.pro_forma_date,
				pi.due_date,
				pi.expected_delivery_date,
				pi.bill_from,
				pi.bill_to,
				pi.ship_to,
				pi.terms_conditions,
				pi.additional_note,
				pi.bank_detail_1,
				pi.bank_detail_2,
				pi.is_digital_signature_checked,
				pi.name_of_authorised_signature,
				pi.thanks_and_regards,
				pi.currency_type,
				pi.total_pro_forma_inv_amount,
				pi.created_at,
				pi.updated_at     
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 
				ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id 				
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY pi.id ASC";
        $query_po_2=$this->client_db->query($sql_po_2,false);
        $po_pi_list=$query_po_2->result_array();

        $sql_po_3="SELECT 
				t.id,
				t.po_pro_forma_invoice_id,
				t.additional_charge_id,
				t.additional_charge_name,
				t.price,
				t.discount,
				t.is_discount_p_or_a,
				t.gst,
				t.created_at      
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_pro_forma_invoice_additional_charges AS t ON t.po_pro_forma_invoice_id=pi.id
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t.id ASC";
        $query_po_3=$this->client_db->query($sql_po_3,false);
        $po_pi_additional_charges_list=$query_po_3->result_array();

        $sql_po_4="SELECT 
				t.id,
				t.po_pro_forma_invoice_id,
				t.product_id,
				t.product_name,
				t.product_sku,
				t.unit,
				t.unit_type,
				t.quantity,
				t.price,
				t.discount,
				t.is_discount_p_or_a,
				t.gst,
				t.created_at         
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_pro_forma_invoice_product AS t ON t.po_pro_forma_invoice_id=pi.id
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t.id ASC";
        $query_po_4=$this->client_db->query($sql_po_4,false);
        $po_pi_product=$query_po_4->result_array();


        $sql_po_5="SELECT 
				t.id,
				t.lead_opportunity_wise_po_id,
				t.invoice_type,
				t.po_custom_invoice,
				t.invoice_no,
				t.invoice_date,
				t.due_date,
				t.expected_delivery_date,
				t.bill_from,
				t.bill_to,
				t.ship_to,
				t.terms_conditions,
				t.additional_note,
				t.bank_detail_1,
				t.bank_detail_2,
				t.is_digital_signature_checked,
				t.name_of_authorised_signature,
				t.thanks_and_regards,
				t.currency_type,
				t.total_inv_amount,
				t.created_at,
				t.updated_at          
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_invoice AS t ON t.lead_opportunity_wise_po_id=t1.id
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t.id ASC";
        $query_po_5=$this->client_db->query($sql_po_5,false);
        $po_inv=$query_po_5->result_array();

        $sql_po_6="SELECT 
				t.id,
				t.po_invoice_id,
				t.additional_charge_id,
				t.additional_charge_name,
				t.price,
				t.discount,
				t.is_discount_p_or_a,
				t.gst,
				t.created_at           
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_invoice AS inv ON inv.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_invoice_additional_charges AS t ON t.po_invoice_id=inv.id
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t.id ASC";
        $query_po_6=$this->client_db->query($sql_po_6,false);
        $po_inv_additional_charges=$query_po_6->result_array();


        $sql_po_7="SELECT 
				t.id,
				t.po_invoice_id,
				t.product_id,
				t.product_name,
				t.product_sku,
				t.unit,
				t.unit_type,
				t.quantity,
				t.price,
				t.discount,
				t.is_discount_p_or_a,
				t.gst,
				t.created_at            
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_invoice AS inv ON inv.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_invoice_product AS t ON t.po_invoice_id=inv.id
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t.id ASC";
        $query_po_7=$this->client_db->query($sql_po_7,false);
        $po_inv_product=$query_po_7->result_array();


        $sql_po_8="SELECT 
				t.id,
				t.lead_opportunity_wise_po_id,
				t.received_date,
				t.currency_type,
				t.amount,
				t.payment_mode_id,
				t.narration,
				t.created_at           
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_payment_received AS t ON t.lead_opportunity_wise_po_id=t1.id
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t.id ASC";
        $query_po_8=$this->client_db->query($sql_po_8,false);
        $po_payment_received_list=$query_po_8->result_array();


        $sql_po_9="SELECT 
				t.id,
				t.lead_opportunity_wise_po_id,
				t.payment_type,
				t.total_amount,
				t.total_payment_received,
				t.total_balance_payment,
				t.created_at,
				t.updated_at            
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_payment_terms AS t ON t.lead_opportunity_wise_po_id=t1.id
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t.id ASC";
        $query_po_9=$this->client_db->query($sql_po_9,false);
        $po_payment_terms_list=$query_po_9->result_array();

        $sql_po_10="SELECT 
				t.id,
				t.po_payment_term_id,
				t.payment_mode_id,
				t.payment_date,
				t.currency_type,
				t.amount,
				t.payment_received,
				t.balance_payment,
				t.narration,
				t.is_payment_received,
				t.payment_received_datetime,
				t.created_at,
				t.updated_at             
				FROM lead_opportunity_wise_po AS t1 
				INNER JOIN lead_opportunity AS t2 ON t1.lead_opportunity_id=t2.id 
				INNER JOIN lead ON t2.lead_id=lead.id 
				INNER JOIN po_pro_forma_invoice AS pi ON pi.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_payment_terms AS p_term ON p_term.lead_opportunity_wise_po_id=t1.id 
				INNER JOIN po_payment_term_details AS t ON t.po_payment_term_id=p_term.id
				WHERE pi.pro_forma_no!='' $subsql
				ORDER BY t.id ASC";
        $query_po_10=$this->client_db->query($sql_po_10,false);
        $po_payment_term_details_list=$query_po_10->result_array();
		// PO
		// ---------------------------
		
        return array(        			
        			// 'lead_wise_product_service_list'=>$lead_wise_product_service_list,
        			// 'lead_stage_log_list'=>$lead_stage_log_list,
        			// 'lead_status_log_list'=>$lead_status_log_list,
        			// 'lead_assigned_user_log_list'=>$lead_assigned_user_log_list,
        			// 'lopportunity_stage_list'=>$lopportunity_stage_list,
        			// 'lead_history_list'=>$lead_history_list,        			
        			// 'opportunity_product_list'=>$opportunity_product_list
        			// 'lead_list'=>$lead_list,
        			'lead_opportunity_list'=>$lead_opportunity_list,
        			'quotation_list'=>$quotation_list,
        			'lead_opportunity_wise_po_list'=>$lead_opportunity_wise_po_list,
        			'po_pi_list'=>$po_pi_list,
        			'po_pi_additional_charges_list'=>$po_pi_additional_charges_list,
        			'po_pi_product'=>$po_pi_product,
        			'po_inv'=>$po_inv,
        			'po_inv_additional_charges'=>$po_inv_additional_charges,
        			'po_inv_product'=>$po_inv_product,
        			'po_payment_received_list'=>$po_payment_received_list,
        			'po_payment_terms_list'=>$po_payment_terms_list,
        			'po_payment_term_details_list'=>$po_payment_term_details_list
					);
    }
    // ---- CUSTOMER LIST FOR AUTOSOL SYNC ----
    // ----------------------------------------

	public function get_last_api_hit_info($arg)
    {	
		$client_info=$arg['client_info'];
		$api_name=$arg['api_name'];
		$client_id=$client_info->client_id;    			
		$sql="SELECT id,api_name,client_id,ip_address,created_at 
		FROM tbl_external_api_hit_log WHERE api_name='".$api_name."' AND client_id='".$client_id."' ORDER BY 1 DESC LIMIT 1";
        $query=$this->fsas_db->query($sql,false);
		if($query){
			return $query->row_array(); 
		}
		else{
			return array();
		}        
    }

	public function add_external_api_hit_log($data)
	{
		if($this->fsas_db->insert('tbl_external_api_hit_log',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

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
		$subsqlInner='';	
		if($arg['customer_id'])
		{
			$subsql .=" AND lead.customer_id='".$arg['customer_id']."'";
		}
		if($arg['show_data_type'])
		{
			if($arg['show_data_type']=='ALL'){
				$subsql .="";
			} elseif($arg['show_data_type']=='OPEN'){
				$subsql .=" AND lead.current_stage_id='1'";
			} elseif($arg['show_data_type']=='ORDER'){
				$subsql .=" AND lead.current_stage_id='4'";
			}
		}

		$start=$arg['start'];
		$limit=$arg['limit'];

		if(trim($start)!=''){
			$limitcond="LIMIT ".$start.",".$limit;
		} else {
			$limitcond="";
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
			
			$subsqlInner WHERE lead.status='1' $subsql GROUP BY lead.id ORDER BY lead.id DESC ".$limitcond;

		// $sql="SELECT t1.id,t1.title,t1.current_stage
		// 	FROM lead AS t1 		
		// 	WHERE t1.status='1' $subsql ORDER BY t1.id DESC ".$limitcond;
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
}

?>