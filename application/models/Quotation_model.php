<?php
class Quotation_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() {
        parent::__construct();
		// $this->load->database();
		$this->class_name=$this->router->fetch_class();
		$this->user_arr=array();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);

    }


	function CreateQuotation($data,$client_info=array())
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

		if($this->client_db->insert('quotation',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}
	
	function CreateQuotationTimeCustomerLog($data,$client_info=array())
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

		if($this->client_db->insert('quotation_time_customer_log',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function UpdateQuotationTimeCompanyInfoLogByQuotationId($data,$id,$client_info=array())
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

		$this->client_db->where('quotation_id',$id);
		if($this->client_db->update('quotation_time_company_setting_log',$data))
		{
			return true;
		}
		else
		{
			return false;
		}		

	}

	function CreateQuotationTimeCompanyInfoLog($data,$client_info=array())
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

		if($this->client_db->insert('quotation_time_company_setting_log',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function CreateQuotationTimeTermsLog($data,$client_info=array())
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
		
		if($this->client_db->insert('quotation_time_terms_and_conditions_log',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}
	function CreateQuotationPDF($data)
	{

		if($this->client_db->insert('quotation_pdf',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	function UpdateQuotation($data,$id,$client_info=array())
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
		if($this->client_db->update('quotation',$data))
		{
			// echo $last_query = $this->client_db->last_query();die();
			return true;
		}
		else
		{
			return false;
		}		

	}
	
	function UpdateQuotationTermsLog($data,$id,$client_info=array())
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
		if($this->client_db->update('quotation_time_terms_and_conditions_log',$data))
		{
			return true;
		}
		else
		{
			return false;
		}		

	}

	function UpdateQuotationProduct($data,$id)
	{

		$this->client_db->where('id',$id);

		if($this->client_db->update('quotation_product',$data))
		{
			return true;
		}
		else
		{
			return false;
		}		

	}
	
	public function get_company_setting_log($quotation_id,$client_info=array())
	{
		if($this->class_name=='download')
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
			quotation_id,
			logo,
			name,
			address,
			city,
			state,
			country,
			pin,
			about_company,
			gst_number,
			pan_number,
			ceo_name,
			contact_person,
			email1,
			email2,
			mobile1,
			mobile2,
			phone1,
			phone2,
			website,
			quotation_cover_letter_body_text,
			quotation_terms_and_conditions,
			quotation_cover_letter_footer_text,
			quotation_bank_details1,
			quotation_bank_details2,
			bank_credit_to,
			bank_name,
			bank_acount_number,
			bank_branch_name,
			bank_branch_code,
			bank_ifsc_code,
			bank_swift_number,
			bank_telex,
			bank_address,
			correspondent_bank_name,
			correspondent_bank_swift_number,
			correspondent_account_number 
			FROM quotation_time_company_setting_log 
		WHERE quotation_id='".$quotation_id."'";
		$query = $this->client_db->query($sql,false);        
        //$last_query = $this->client_db->last_query();
        //return $query->row();
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
	}

	public function get_customer_log($quotation_id,$client_info=array())
	{
		if($this->class_name=='download')
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
			quotation_id,
			first_name,
			last_name,
			contact_person,
			designation,
			email,
			alt_email,
			mobile,
			alt_mobile,
			office_phone,
			website,
			company_name,
			address,
			city,
			state,
			country,
			zip,
			gst_number FROM quotation_time_customer_log 
		WHERE quotation_id='".$quotation_id."'";
		$query = $this->client_db->query($sql,false);        
        //$last_query = $this->client_db->last_query();
        //return $query->row();

		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
	}

	public function get_terms_log($quotation_id,$is_display_in_quotation='',$client_info=array())
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

		$subsql ="";
		if($is_display_in_quotation)
		{
			$subsql .= " AND is_display_in_quotation='".$is_display_in_quotation."'";
		}
		$sql="SELECT id,quotation_id,name,value,is_display_in_quotation FROM quotation_time_terms_and_conditions_log 
		WHERE quotation_id='".$quotation_id."' $subsql ";
		$query = $this->client_db->query($sql,false);        
        //$last_query = $this->client_db->last_query();
        //return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function get_lead_opportunity($id,$client_info=array())
	{
		if($this->class_name=='download')
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
				source_id,
				opportunity_title,
				deal_value,
				deal_value_as_per_purchase_order,
				currency_type,
				create_date,
				modify_date,
				status,
				is_active,
				is_deleted FROM lead_opportunity 
		WHERE id='".$id."'";
		$query = $this->client_db->query($sql,false);        
        //$last_query = $this->client_db->last_query();
        //return $query->row();
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
	}

	public function GetQuotationData($id,$client_info=array())
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
			t1.id,
			t1.opportunity_id,
			t1.customer_id,
			t1.quote_title,
			t1.quote_date,
			t1.quote_no,
			t1.quote_valid_until,
			t1.is_extermal_quote,
			t1.file_name,
			t1.currency_type,
			t1.is_product_image_show_in_quotation,
			t1.is_product_youtube_url_show_in_quotation,
			t1.is_product_brochure_attached_in_quotation,
			t1.is_hide_total_net_amount_in_quotation,
			t1.is_hide_gst_in_quotation,
			t1.is_show_gst_extra_in_quotation,
			t1.is_consolidated_gst_in_quotation,
			t1.is_company_brochure_attached_in_quotation,
			t1.is_quotation_bank_details1_send,
			t1.is_quotation_bank_details2_send,
			t1.is_gst_number_show_in_quotation,
			t1.letter_to,
			t1.letter_subject,
			t1.letter_body_text,
			t1.letter_footer_text,
			t1.letter_terms_and_conditions,
			t1.shipping_terms,
			t1.payment_terms,
			t1.letter_thanks_and_regards,
			t1.is_digital_signature_checked,
			t1.name_of_authorised_signature,
			t1.create_date,
			t1.modify_date,
			GROUP_CONCAT(CONCAT(t2.title,'#',t2.file_name)) AS q_photos 
			FROM quotation AS t1 
			LEFT JOIN quotation_photo AS t2 ON t1.id=t2.quotation_id
			WHERE t1.id='".$id."' GROUP BY t1.id";
		// echo $sql; die();
		$query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();die();
		$row_array=array();
		if($query){
			foreach($query->result() as $row)
			{
				$company_setting_log=$this->get_company_setting_log($row->id,$client_info);
				$customer_log=$this->get_customer_log($row->id,$client_info);
				$terms_log=$this->get_terms_log($row->id,'',$client_info);
				$terms_log_only_display_in_quotation=$this->get_terms_log($row->id,'Y',$client_info);
				$lead_opportunity=$this->get_lead_opportunity($row->opportunity_id,$client_info);
				$product_list=$this->GetQuotationProductList($row->id,$client_info);
				$quotation=array(
								'id'=> $row->id,
								'opportunity_id'=> $row->opportunity_id,
								'customer_id'=> $row->customer_id,
								'quote_title'=> $row->quote_title,
								'quote_date'=> $row->quote_date,
								'quote_no'=> $row->quote_no,
								'quote_valid_until'=> $row->quote_valid_until,
								'is_extermal_quote'=>$row->is_extermal_quote,
								'file_name'=> $row->file_name,
								'currency_type'=> $row->currency_type,
								'is_product_image_show_in_quotation'=>$row->is_product_image_show_in_quotation,
								'is_product_youtube_url_show_in_quotation'=>$row->is_product_youtube_url_show_in_quotation,
								'is_product_brochure_attached_in_quotation'=>$row->is_product_brochure_attached_in_quotation,
								'is_hide_total_net_amount_in_quotation'=>$row->is_hide_total_net_amount_in_quotation,
								'is_hide_gst_in_quotation'=>$row->is_hide_gst_in_quotation,
								'is_show_gst_extra_in_quotation'=>$row->is_show_gst_extra_in_quotation,
								'is_consolidated_gst_in_quotation'=>$row->is_consolidated_gst_in_quotation,
								'is_company_brochure_attached_in_quotation'=>$row->is_company_brochure_attached_in_quotation,
								'is_quotation_bank_details1_send'=>$row->is_quotation_bank_details1_send,
								'is_quotation_bank_details2_send'=>$row->is_quotation_bank_details2_send,
								'is_gst_number_show_in_quotation'=>$row->is_gst_number_show_in_quotation,
								'letter_to'=>$row->letter_to,
								'letter_subject'=>$row->letter_subject,
								'letter_body_text'=>$row->letter_body_text,
								'letter_footer_text'=>$row->letter_footer_text,
								'letter_terms_and_conditions'=>$row->letter_terms_and_conditions,
								'shipping_terms'=>$row->shipping_terms,
								'payment_terms'=>$row->payment_terms,
								'letter_thanks_and_regards'=>$row->letter_thanks_and_regards,
								'is_digital_signature_checked'=>$row->is_digital_signature_checked,
								'name_of_authorised_signature'=>$row->name_of_authorised_signature,
								'create_date'=>$row->create_date,
								'q_photos'=>$row->q_photos
								);

				$lead_opportunity_data=array(
								'lead_id'=>$lead_opportunity->lead_id,
								'opportunity_title'=>$lead_opportunity->opportunity_title,
								//'approx_deal_value'=>$lead_opportunity->approx_deal_value,
								'status'=>$lead_opportunity->status,
								'currency_type_id'=>$lead_opportunity->currency_type,
								);

				$quotation_time_company_setting_log=array(
								'id'=>$company_setting_log->id,
								'logo'=>$company_setting_log->logo,
								'name'=>$company_setting_log->name,
								'address'=>$company_setting_log->address,
								'city'=>$company_setting_log->city,
								'state'=>$company_setting_log->state,
								'country'=>$company_setting_log->country,
								'pin'=>$company_setting_log->pin,
								'about_company'=>$company_setting_log->about_company,
								'gst_number'=>$company_setting_log->gst_number,
								'pan_number'=>$company_setting_log->pan_number,
								'ceo_name'=>$company_setting_log->ceo_name,
								'contact_person'=>$company_setting_log->contact_person,
								'email1'=>$company_setting_log->email1,
								'email2'=>$company_setting_log->email2,
								'mobile1'=>$company_setting_log->mobile1,
								'mobile2'=>$company_setting_log->mobile2,
								'phone1'=>$company_setting_log->phone1,
								'phone2'=>$company_setting_log->phone2,
								'website'=>$company_setting_log->website,
								'quotation_cover_letter_body_text'=>$company_setting_log->quotation_cover_letter_body_text,
								'quotation_terms_and_conditions'=>$company_setting_log->quotation_terms_and_conditions,
								'quotation_cover_letter_footer_text'=>$company_setting_log->quotation_cover_letter_footer_text,
								'quotation_bank_details1'=>$company_setting_log->quotation_bank_details1,
								'quotation_bank_details2'=>$company_setting_log->quotation_bank_details2,
								'bank_credit_to'=>$company_setting_log->bank_credit_to,
								'bank_name'=>$company_setting_log->bank_name,
								'bank_acount_number'=>$company_setting_log->bank_acount_number,
								'bank_branch_name'=>$company_setting_log->bank_branch_name,
								'bank_branch_code'=>$company_setting_log->bank_branch_code,
								'bank_ifsc_code'=>$company_setting_log->bank_ifsc_code,
								'bank_swift_number'=>$company_setting_log->bank_swift_number,
								'bank_telex'=>$company_setting_log->bank_telex,
								'bank_address'=>$company_setting_log->bank_address,
								'correspondent_bank_name'=>$company_setting_log->correspondent_bank_name,
								'correspondent_bank_swift_number'=>$company_setting_log->correspondent_bank_swift_number,
								'correspondent_account_number'=>$company_setting_log->correspondent_account_number
								);

				$quotation_time_customer_log=array(
								'id'=>$customer_log->id,
								'first_name'=>$customer_log->first_name,
								'last_name'=>$customer_log->last_name,
								'contact_person'=>$customer_log->contact_person,
								'designation'=>$customer_log->designation,
								'email'=>$customer_log->email,
								'alt_email'=>$customer_log->alt_email,
								'mobile'=>$customer_log->mobile,
								'alt_mobile'=>$customer_log->alt_mobile,
								'office_phone'=>$customer_log->office_phone,
								'website'=>$customer_log->website,
								'company_name'=>$customer_log->company_name,
								'address'=>$customer_log->address,
								'city'=>$customer_log->city,
								'state'=>$customer_log->state,
								'country'=>$customer_log->country,
								'zip'=>$customer_log->zip,
								'gst_number'=>$customer_log->gst_number
								);

				

				$row_array =array(
									'quotation'=>$quotation,
									'lead_opportunity_data'=>$lead_opportunity_data,
									'company_log'=>$quotation_time_company_setting_log,
									'customer_log'=>$quotation_time_customer_log,
									'terms_log'=>$terms_log,
									'terms_log_only_display_in_quotation'=>$terms_log_only_display_in_quotation,
									'product_list'=>$product_list
									);
			}
		}
		

		return $row_array;
	}
	public function CheckTermsConditions($opportunity_id)
	{

		$sql="SELECT COUNT(*) AS tot,terms_condition FROM `quotation` WHERE opportunity_id='".$opportunity_id."' AND save_terms_conditions='1' ORDER BY id DESC";

		$result=$this->client_db->query($sql);	

		//return $result->row();

		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}

	}
	
	public function GetBankDetails()
	{

		$sql="select * from `bank_details`";
		$result=$this->client_db->query($sql);	
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}

	}
	
	public function GetStatutoryDetails()
	{

		$sql="select * from `statutory_details`";

		$result=$this->client_db->query($sql);	

		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}

	}
	
	public function GetQuotationProductList($quotation_id,$client_info=array())
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
				t1.id,
				t1.quotation_id,
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
				t1.image_for_show,
				t1.is_youtube_video_url_show,
				t1.is_brochure_attached,
				t1.sort,
				t2.description,
				t2.hsn_code,
				t2.youtube_video,
				GROUP_CONCAT(CONCAT(t3.id,'#',t3.file_name)) AS image,
				t4.file_name AS brochure  FROM quotation_product AS t1
				LEFT JOIN product_varient AS t2 ON t1.product_id=t2.id
				LEFT JOIN product_images AS t3 ON t1.product_id=t3.varient_id
				LEFT JOIN product_pdf AS t4 ON t1.product_id=t4.varient_id 
				WHERE t1.quotation_id='".$quotation_id."' GROUP BY t1.id,t1.product_id
				ORDER BY t1.sort";		
		// $result=$this->client_db->query($sql);	
		// return $result->result();
		// echo $sql;die();
		$query=$this->client_db->query($sql,false);
		$row_array=array();
		if($query)
		{
			foreach($query->result_array() as $row)
			{
				$image_for_show_files='';
				if($row['image_for_show'])
				{
					$image_for_show_arr=unserialize($row['image_for_show']);
					$image_for_show_str=implode(",", $image_for_show_arr);
					$sql_p="SELECT GROUP_CONCAT(file_name) AS image_for_show_files FROM product_images WHERE id IN ($image_for_show_str)";
					$query_p=$this->client_db->query($sql_p);	
					$row_p=$query_p->row();
					$image_for_show_files=$row_p->image_for_show_files;
				}
				
				$row_array[]=(object) array(
					"id"=>$row['id'],
					"quotation_id"=>$row['quotation_id'],
					"product_id"=>$row['product_id'],
					"product_name"=>$row['product_name'],
					"product_sku"=>$row['product_sku'],
					"unit"=>$row['unit'],
					"unit_type"=>$row['unit_type'],
					"quantity"=>$row['quantity'],
					"price"=>$row['price'],
					"discount"=>$row['discount'],
					"is_discount_p_or_a"=>$row['is_discount_p_or_a'],
					"gst"=>$row['gst'],
					"image_for_show"=>$row['image_for_show'],
					"image_for_show_files"=>$image_for_show_files,
					"is_youtube_video_url_show"=>$row['is_youtube_video_url_show'],
					"is_brochure_attached"=>$row['is_brochure_attached'],
					"sort"=>$row['sort'],
					"description"=>$row['description'],
					"hsn_code"=>$row['hsn_code'],
					"youtube_video"=>$row['youtube_video'],
					"image"=>$row['image'],
					"brochure"=>$row['brochure']
				);
			}
		}
				
		return $row_array;
	}

	//public function GetQuotationProduct($quotation_id,$pid)
	public function GetQuotationProduct($id,$client_info=array())
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
			t1.id,
			t1.quotation_id,
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
			t4.file_name AS brochure  FROM quotation_product AS t1
			LEFT JOIN product_varient AS t2 ON t1.product_id=t2.id
			LEFT JOIN product_images AS t3 ON t1.product_id=t3.varient_id
			LEFT JOIN product_pdf AS t4 ON t1.product_id=t4.varient_id
			WHERE t1.id='".$id."'";
		$result=$this->client_db->query($sql);	
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	public function GetQuotationProductRow($id)
	{
		$sql="SELECT 
			t1.id,
			t1.quotation_id,
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
			t1.image_for_show,
			t1.is_youtube_video_url_show,
			t1.is_brochure_attached 
			FROM quotation_product AS t1
			WHERE t1.id='".$id."'";		
		$result=$this->client_db->query($sql);	
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	public function LastQuotationID($opportunity_id)
	{

		$sql="select count(*) as id from quotation_pdf where opportunity_id='".$opportunity_id."'";

		$result=$this->client_db->query($sql);	
		//return $result->row();

		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	

	function CreateQuotationProduct($quotation_product_data,$client_info=array())
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

		if($this->client_db->insert('quotation_product',$quotation_product_data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}

	public function DeleteLead($id)
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
	public function GetCurrencyList()
	{
		$sql="SELECT * FROM currency";
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}	
	
	public function GetQuotationList($opportunity_id)
	{
		$sql="SELECT * FROM `quotation` where `opportunity_id`='".$opportunity_id."'";
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_last_quote_no()
	{
		$sql="SELECT * FROM `quotation` ORDER BY id DESC LIMIT 1";
		$result=$this->client_db->query($sql);

		if($result){
			$row=$result->row_array();
			return $row['quote_no'];
		} else {
			return "";
		}
		
	}

	public function get_terms_and_conditions($table,$client_info=array())
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

		$sql="SELECT id,name,value FROM ".$table." ORDER BY id";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function delete_product($id,$client_info=array())
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
		if($this->client_db->delete('quotation_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function update_product($data_post,$id,$client_info=array())
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
		if($this->client_db->update('quotation_product',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}


	public function deleteQuotationTermsLog($qid,$client_info=array())
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

		$this->client_db->where('quotation_id',$qid);
		if($this->client_db->delete('quotation_time_terms_and_conditions_log'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function UpdateQuotationProductByQuotationId($data_post,$quotation_id,$client_info=array())
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

		$this->client_db->where('quotation_id',$quotation_id);
		if($this->client_db->update('quotation_product',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function UpdateQuotationChargesByOppId($data_post,$opp_id,$client_info=array())
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

		$this->client_db->where('opportunity_id',$opp_id);
		if($this->client_db->update('opportunity_additional_charges',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	function addQuotationPhoto($post_data)
	{

		if($this->client_db->insert('quotation_photo',$post_data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}

	public function GetQuotationPhoto($quotation_id)
	{
		$subsql="";
		$sql="SELECT id,
				quotation_id,
				title,
				file_name 
				FROM quotation_photo WHERE quotation_id='".$quotation_id."' $subsql 
				ORDER BY id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function DelQuotationPhoto($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('quotation_photo'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	function UpdateQuotationPhoto($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('quotation_photo',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function GetQuotationPhotoRow($id)
	{
		$sql="SELECT id,
				quotation_id,
				title,
				file_name 
				FROM quotation_photo WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		//return $result->row();

		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	function UpdateQuotationProductByQid($data,$q_id,$client_info=array())
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

		$this->client_db->where('quotation_id',$q_id);

		if($this->client_db->update('quotation_product',$data))
		{
			return true;
		}
		else
		{
			return false;
		}		

	}

	public function qutation_delete($lead_id,$quotation_id,$opportunity_id,$client_info=array())
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
		
		$this->client_db->trans_begin();

		$this->client_db->query("DELETE FROM lead_opportunity WHERE id='".$opportunity_id."'");
		$this->client_db->query("DELETE FROM opportunity_product WHERE opportunity_id='".$opportunity_id."'");
		$this->client_db->query("DELETE FROM opportunity_additional_charges WHERE opportunity_id='".$opportunity_id."'");
		$this->client_db->query("DELETE FROM quotation WHERE id='".$quotation_id."'");
		$this->client_db->query("DELETE FROM quotation_product WHERE quotation_id='".$quotation_id."'");
		$this->client_db->query("DELETE FROM quotation_time_customer_log WHERE quotation_id='".$quotation_id."'");
		$this->client_db->query("DELETE FROM quotation_time_company_setting_log WHERE quotation_id='".$quotation_id."'");
		$this->client_db->query("DELETE FROM quotation_time_terms_and_conditions_log WHERE quotation_id='".$quotation_id."'");
		$this->client_db->query("DELETE FROM quotation_photo WHERE quotation_id='".$quotation_id."'");
		$this->client_db->query("DELETE FROM lead_comment WHERE lead_id='".$lead_id."' AND lead_opportunity_id='".$opportunity_id."'");		

		if ($this->client_db->trans_status() === FALSE)
		{
		    $this->client_db->trans_rollback();
		}
		else
		{
		    $this->client_db->trans_commit();
		}
	}


	public function update_additional_charges($data_post,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('opportunity_additional_charges',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function qutation_delete2($arg=array(),$client_info=array())
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
		
		$lead_id=$arg['lead_id'];
		$quotation_id=$arg['quotation_id'];
		$opportunity_id=$arg['opportunity_id'];
		$table_arr=$arg['table_arr'];


		$this->client_db->trans_begin();

		if(in_array("lead_opportunity", $table_arr)){
			$this->client_db->query("DELETE FROM lead_opportunity WHERE id='".$opportunity_id."'");
		}
		if(in_array("opportunity_product", $table_arr)){
			$this->client_db->query("DELETE FROM opportunity_product WHERE opportunity_id='".$opportunity_id."'");
		}
		if(in_array("opportunity_additional_charges", $table_arr)){
			$this->client_db->query("DELETE FROM opportunity_additional_charges WHERE opportunity_id='".$opportunity_id."'");
		}
		if(in_array("quotation", $table_arr)){
			$this->client_db->query("DELETE FROM quotation WHERE id='".$quotation_id."'");
		}
		if(in_array("quotation_product", $table_arr)){
			$this->client_db->query("DELETE FROM quotation_product WHERE quotation_id='".$quotation_id."'");
		}
		if(in_array("quotation_time_customer_log", $table_arr)){
			$this->client_db->query("DELETE FROM quotation_time_customer_log WHERE quotation_id='".$quotation_id."'");
		}
		if(in_array("quotation_time_company_setting_log", $table_arr)){
			$this->client_db->query("DELETE FROM quotation_time_company_setting_log WHERE quotation_id='".$quotation_id."'");
		}
		if(in_array("quotation_time_terms_and_conditions_log", $table_arr)){
			$this->client_db->query("DELETE FROM quotation_time_terms_and_conditions_log WHERE quotation_id='".$quotation_id."'");
		}
		if(in_array("quotation_photo", $table_arr)){
			$this->client_db->query("DELETE FROM quotation_photo WHERE quotation_id='".$quotation_id."'");
		}
		if(in_array("lead_comment", $table_arr)){
			$this->client_db->query("DELETE FROM lead_comment WHERE lead_id='".$lead_id."' AND lead_opportunity_id='".$opportunity_id."'");
		}			

		if ($this->client_db->trans_status() === FALSE)
		{
		    $this->client_db->trans_rollback();
		}
		else
		{
		    $this->client_db->trans_commit();
		}
	}

}
?>