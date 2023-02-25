<?php
class Setting_model extends CI_Model
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

	function UpdateCompany($user_data,$id,$client_info=array())
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
		if($this->client_db->update('company_setting',$user_data))
		{
			// echo $this->client_db->last_query();  die('ok123');
			return true;		  
		}

		else
		{
			// echo $this->client_db->last_query();  die('ok555');
			return false;
		}		
	}

	public function GetCompanyData($client_info=array())
	{	
		// if($this->class_name=='cronjobs' || $this->class_name=='capture' || $this->class_name=='account' || $this->class_name=='login' || $this->class_name=='download' || $this->class_name=='preview_quotation' || $this->class_name=='rest_user')
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
		// echo $last_query = $this->client_db->last_query();  die('ok');
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
		
	}

	function AddDomesticterms($data)
	{
		if($this->client_db->insert('terms_and_conditions_domestic_quotation',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function EditDomesticterms($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('terms_and_conditions_domestic_quotation',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	function AddInternationalterms($data)
	{
		if($this->client_db->insert('terms_and_conditions_export_quotation',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function EditInternationalterms($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('terms_and_conditions_export_quotation',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	public function get_domestic_terms_details($id)
	{		
		$sql="SELECT * FROM terms_and_conditions_domestic_quotation WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
		
	}

	public function DeleteDomesticTerms($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('terms_and_conditions_domestic_quotation');
		return true;
	}

	public function get_international_terms_details($id)
	{		
		$sql="SELECT * FROM terms_and_conditions_export_quotation WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}
	public function DeleteInternationalTerms($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('terms_and_conditions_export_quotation');
		return true;
	}

	public function chk_name_is_exist_in_iterms($name)
	{		
		$sql="SELECT * FROM terms_and_conditions_export_quotation WHERE replace(LOWER(name) , ' ','')='".str_replace(' ', '', trim(strtolower($name)))."'";
		$query=$this->client_db->query($sql);
		
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
	}

	public function chk_name_is_exist_in_dterms($name)
	{		
		$sql="SELECT * FROM terms_and_conditions_domestic_quotation WHERE replace(LOWER(name) , ' ','')='".str_replace(' ', '', trim(strtolower($name)))."'";
		$query=$this->client_db->query($sql);
		
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
	}

	public function get_gmail_for_sync($user_id)
	{		
		$sql="SELECT gmail_address FROM user_wise_gmail_for_sync WHERE user_id='".$user_id."' LIMIT 1";
		$result=$this->client_db->query($sql);
		if($result){
			if($result->num_rows()){
				return $result->row()->gmail_address;
			}
			else{
				return '';
			}
		}
		else{
			return '';
		}

		
	}

	public function get_all_gmail_for_sync($user_id)
	{		
		$sql="SELECT id,
				user_id,
				gmail_address,
				last_sync_at,
				created_at FROM 
				user_wise_gmail_for_sync WHERE user_id='".$user_id."' LIMIT 1";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}

	}

	public function update_gmail_sync_by_user($user_id,$data)
	{
		$this->client_db->where('user_id',$user_id);
		if($this->client_db->update('user_wise_gmail_for_sync',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	public function GetSmtpData($client_info=array())
	{
		if($this->class_name=='cronjobs')
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

		$sql="SELECT id,smtp_type,host,port,username,password 
		FROM smtp_settings WHERE is_active='Y' AND is_default='N' LIMIT 1";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}

		// $this->client_db->select('id,smtp_type,username,password');
		// $this->client_db->from('smtp_settings');		
		// $this->client_db->where('is_active','Y');
		// $result=$this->client_db->get();
		// return $result->row_array();
	}

	public function GetDefaultSmtpData($client_info=array())
	{
		if($this->class_name=='cronjobs')
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

	function AddSmtpCredentials($data)
	{
		if($this->client_db->insert('smtp_settings',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function EditSmtpAllCredentials($data)
	{		
		if($this->client_db->update('smtp_settings',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}
	public function EditSmtpCredentials($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('smtp_settings',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}
	public function is_smtp_type_exist($smtp_type)
	{
		$sql="SELECT id 
		FROM smtp_settings WHERE smtp_type='".$smtp_type."'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->num_rows();
		}
		else{
			return 0;
		}

	}
	public function GetSmtpList()
	{
		$sql="SELECT id,smtp_type,username,password,is_active,is_default,created_at,updated_at
		FROM smtp_settings WHERE is_default='N' ORDER BY smtp_type";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}

	}

	public function GetSmtpCredentialsDetails($id)
	{
		$sql="SELECT id,smtp_type,host,port,username,password,is_active,created_at,updated_at 
		FROM smtp_settings WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}		
	}

	public function DeleteSmtpCredentials($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('smtp_settings');
		return true;
	}

	// ----------------------------------------------
	public function GetC2cpList()
	{
		$sql="SELECT 
		t1.id,
		t1.user_id,
		t1.office_no,
		t1.caller_name,
		t1.mobile AS personal_no,
		t1.role,
		t1.escalation,
		t1.created_at,
		t1.updated_at,
		t2.name
		FROM c2c_settings AS t1 
		INNER JOIN user AS t2 ON t1.user_id=t2.id
		WHERE 1=1 ORDER BY id";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}	

	}
	function AddC2cCredentials($data)
	{
		if($this->client_db->insert('c2c_settings',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}
	
	public function EditC2cCredentials($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('c2c_settings',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	public function GetC2cCredentialsDetails($id)
	{
		$sql="SELECT 
		id,
		c2c_service_provider_id,
		user_id,
		office_no,
		caller_name,
		mobile,
		role,
		escalation,
		created_at,
		updated_at 
		FROM c2c_settings 
		WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
			
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}		
	}

	public function DeleteC2cCredentials($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('c2c_settings');
		return true;
	}

	public function is_user_exist_for_c2c($user_id)
	{
		$sql="SELECT id 
		FROM c2c_settings WHERE user_id='".$user_id."'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->num_rows();
		}
		else{
			return 0;
		}

	}

	public function GetC2cCredentialsDetailsByUser($user_id)
	{
		$sql="SELECT 
		id,
		c2c_service_provider_id,
		user_id,
		office_no,
		caller_name,
		mobile,
		role,
		escalation,
		created_at,
		updated_at 
		FROM c2c_settings 
		WHERE user_id='".$user_id."' LIMIT 0,1";
		$result=$this->client_db->query($sql);
		

		if($result){
			if($result->num_rows())
			{
				return $result->row_array();
			}
			else
			{
				return array();
			}
		}
		else{
			return array();
		}
				
	}

	public function get_website_api_assigned_info($client_info=array())
	{
		if($this->class_name=='capture')
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
		$this->client_db->select('id,account_name,assign_rule,assign_to,assign_start');
		$this->client_db->from('website_api_setting');
		$this->client_db->where('id',1);
		$result=$this->client_db->get();
		// echo $last_query = $this->client_db->last_query();  die();
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
		
	}

	public function edit_website_api_assigned($data,$id,$client_info=array())
	{
		if($this->class_name=='cronjobs')
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
		if($this->client_db->update('website_api_setting',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	public function GetActiveLeadStageList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.class_name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active_lead
		FROM opportunity_stage AS t1 		
		WHERE t1.is_active_lead='Y' AND t1.is_deleted='N' ORDER BY t1.sort";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function AddLeadStage($data)
	{
		if($this->client_db->insert('opportunity_stage',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function UpdateLeadStage($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('opportunity_stage',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function GetLeadStage($stage_id)
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.class_name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active_lead
		FROM opportunity_stage AS t1 		
		WHERE t1.id='".$stage_id."'";
		$result=$this->client_db->query($sql);
			
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}	
	}

	public function GetLeadStageSortWise($sortBy)
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.class_name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active_lead
		FROM opportunity_stage AS t1 		
		WHERE t1.sort>='".$sortBy."' AND t1.is_deleted='N' ORDER BY t1.sort";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}	
	}

	public function get_custom_stage_info($client_info=array())
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
		t1.name,
		t1.class_name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active_lead
		FROM opportunity_stage AS t1 		
		WHERE t1.is_system_generated='N' AND t1.is_deleted='N' ORDER BY t1.sort";
		$result=$this->client_db->query($sql);
		$cus_id_arr=array();
		

		if($result){
			foreach($result->result_array() AS $row)
			{
				array_push($cus_id_arr, $row['id']);
			}	
			if(count($cus_id_arr)){
				return array('id_str'=>implode(',', $cus_id_arr),'id_arr'=>$cus_id_arr);
			}
			else{
				return array('id_str'=>'','id_arr'=>array());
			}
		}
		else{
			return array('id_str'=>'','id_arr'=>array());
		}	
			
	}

	public function GetMyDocumentList()
	{
		$sql="SELECT 
		t1.id,
		t1.title,
		t1.file_name,
		t1.created_on 
		FROM tbl_my_document AS t1 		
		WHERE 1=1 ORDER BY t1.title";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}
			
	}
	function AddMyDocument($data)
	{
		if($this->client_db->insert('tbl_my_document',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function DeleteMyDocument($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('tbl_my_document');
		return true;
	}

	public function getMyDocument($id)
	{		
		$sql="SELECT 
				id,
				title,
				file_name,
				created_on 
			FROM tbl_my_document 
			WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	public function GetMyDocumenForQuotationtList()
	{
		$sql="SELECT 
		t1.id,
		t1.title,
		t1.file_name,
		t1.created_on 
		FROM tbl_my_document AS t1 		
		WHERE (t1.file_name LIKE '%png' || t1.file_name LIKE '%jpg' || t1.file_name LIKE '%jpeg' || t1.file_name LIKE '%gif') ORDER BY t1.title";
		$result=$this->client_db->query($sql);
			
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}	
	}

	public function GetProductGroupList()
	{
		$sql="SELECT 
		t1.id,
		t1.parent_id,
		t1.name,
		t1.is_active,
		if(COUNT(t2.id)>0,'Y','N') AS is_product_tagged,
		if(COUNT(t3.id)>0,'Y','N') AS is_child_tagged
		FROM group_wise_category AS t1 	
		LEFT JOIN product_varient AS t2 ON t1.id=t2.group_id 
		LEFT JOIN group_wise_category AS t3 ON t1.id=t3.parent_id	
		WHERE t1.parent_id='0' GROUP BY t1.id ORDER BY t1.id ";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}		
	}

	function UpdateProductGroup($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('group_wise_category',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function DeleteProductGroup($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('group_wise_category');
		return true;
	}
	function AddProductGroup($data)
	{
		if($this->client_db->insert('group_wise_category',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function GetProductCategoryList()
	{
		$sql="SELECT 
		t1.id,
		t1.parent_id,
		t1.name,
		t1.is_active,
		t2.id AS group_id,
		t2.name AS group_name,
		if(COUNT(t3.id)>0,'Y','N') AS is_product_tagged
		FROM group_wise_category AS t1 	
		INNER JOIN 	group_wise_category AS t2 ON t1.parent_id=t2.id 
		LEFT JOIN product_varient AS t3 ON t1.id=t3.cate_id
		WHERE t1.parent_id>'0' GROUP BY t1.id ORDER BY t1.parent_id,t1.id";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}		
	}

	public function GetProductUnitTypeList()
	{
		$sql="SELECT 
		t1.id,
		t1.type_name AS name,
		if(COUNT(t2.id)>0,'Y','N') AS is_product_tagged
		FROM unit_type AS t1 
		LEFT JOIN product_varient AS t2 ON t1.id=t2.unit_type	 		
		WHERE 1 GROUP BY t1.id ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}	
	}

	function UpdateProductUnitType($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('unit_type',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function DeleteProductUnitType($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('unit_type');
		return true;
	}

	function AddProductUnitType($data)
	{
		if($this->client_db->insert('unit_type',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function GetLeadSourceList()
	{
		$sql="SELECT 
		t1.id,
		t1.parent_id,
		t1.name,
		if(COUNT(t2.id)>0,'Y','N') AS is_lead_tagged
		FROM source AS t1 	
		LEFT JOIN lead AS t2 ON t1.id=t2.source_id	
		WHERE 1 GROUP BY t1.id ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}	
	}

	function UpdateLeadSource($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('source',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function DeleteLeadSource($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('source');
		return true;
	}
	function AddLeadSource($data)
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

	public function GetLeadRegretReasonList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		if(COUNT(t2.id)>0,'Y','N') AS is_lead_tagged
		FROM opportunity_regret_reason AS t1 	
		LEFT JOIN lead AS t2 ON t1.id=t2.lost_reason	
		WHERE 1 GROUP BY t1.id ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}	
	}

	function UpdateLeadRegretReason($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('opportunity_regret_reason',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}
	public function DeleteLeadRegretReason($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('opportunity_regret_reason');
		return true;
	}

	function AddLeadRegretReason($data)
	{
		if($this->client_db->insert('opportunity_regret_reason',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function GetSmsServiceProviderList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.created_on 
		FROM sms_service_provider AS t1 		
		WHERE 1 ORDER BY t1.id";
		$result=$this->client_db->query($sql);
			
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}
	}

	public function GetSmsApiList()
	{
		$sql="SELECT 
		t1.id,
		t1.sender,
		t1.apikey,
		t1.entity_id,
		t1.name 
		FROM sms_api AS t1 		
		WHERE 1 ORDER BY t1.name";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();		
		}
		else{
			return array();
		}
	}

	public function GetSmsApi($id)
	{
		$sql="SELECT 
		t1.id,
		t1.sender,
		t1.apikey,
		t1.entity_id,
		t1.name 
		FROM sms_api AS t1 		
		WHERE t1.id=$id";
		$result=$this->client_db->query($sql);
			
		if($result){
			return $result->row_array();		
		}
		else{
			return array();
		}	
	}

	public function GetSmsTemplateList($api_id='')
	{
		$subsql='';
		if($api_id){
			$subsql .=" AND t1.sms_api_id=$api_id";
		}
		$sql="SELECT 
		t1.id,
		t1.sms_api_id,
		t1.template_id,
		t1.name,
		t1.text 
		FROM sms_template AS t1 		
		WHERE 1 $subsql ORDER BY t1.name";
		$result=$this->client_db->query($sql);
		// echo $last_query = $this->client_db->last_query();  die('ok');
		
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}		
	}

	public function GetSmsTemplate($id='')
	{
		$subsql='';
		if($id){
			$subsql .=" AND t1.id=$id";
		}
		$sql="SELECT 
		t1.id,
		t1.sms_api_id,
		t1.template_id,
		t1.name,
		t1.text,
		t2.sender AS api_sender,
		t2.apikey AS api_apikey,
		t2.entity_id AS api_entity_id,
		t2.name AS api_name
		FROM sms_template AS t1 
		INNER JOIN sms_api AS t2 ON t1.sms_api_id=t2.id		
		WHERE 1 $subsql";
		$result=$this->client_db->query($sql);
		// echo $last_query = $this->client_db->last_query();  die('ok');
			
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}	
	}

	function UpdateSmsTemplate($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('company_setting',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function get_template_variable_list($client_info=array())
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
		$sql="SELECT id,variable_type,reserve_keyword,name FROM tbl_template_variable_list WHERE is_active='Y' ORDER BY variable_type,id ";
		$query=$this->client_db->query($sql);		
		
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}	
	}
	
	public function sms_company_row($client_info=array())
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
		$this->client_db->select('
			company_setting.name AS c_name,
			company_setting.email1 AS c_email,
			company_setting.mobile1 AS c_mobile,
			company_setting.address AS c_address,
			company_setting.pin AS c_zip,
			company_setting.gst_number AS c_gst,
			countries.name as c_country,
			states.name as c_state,
			cities.name as c_city');
		$this->client_db->from('company_setting');
		$this->client_db->join('countries', 'countries.id = company_setting.country_id','left');
		$this->client_db->join('states', 'states.id = company_setting.state_id', 'left');
		$this->client_db->join('cities', 'cities.id = company_setting.city_id', 'left');
		//$this->client_db->join('currency', 'currency.id = company_setting.default_currency', 'left');
		$this->client_db->where('company_setting.id',1);
		$result=$this->client_db->get();
		//echo $last_query = $this->client_db->last_query();  die('ok');
		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}	
	}

	public function kpi_target_by()
	{
		$sql="SELECT 
		kpi_target_by
		FROM kpi_setting GROUP BY kpi_target_by";
		$result=$this->client_db->query($sql);
		// echo $last_query = $this->client_db->last_query();  die('ok');
			
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}	

	}

	public function kpi_target_by_id_first()
	{
		$sql="SELECT 
		kpi_target_by_id
		FROM kpi_setting ORDER BY kpi_target_by_id LIMIT 1";
		// echo $last_query = $this->client_db->last_query();  die('ok');
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}			
	}

	public function GetFunctionalArea()
	{
		$sql="SELECT 
			t1.id,
			t1.name,
			t2.kpi_target_by,
			t2.kpi_target_by_id,
			t2.kpi_ids,
			t2.kpi_names
			FROM functional_area AS t1 
			LEFT JOIN kpi_setting AS t2 ON t1.id=t2.kpi_target_by_id AND  t2.kpi_target_by='F'
			WHERE t1.is_deleted='N'  
			ORDER BY t1.id ASC ";
        $query = $this->client_db->query($sql);
		// echo $last_query = $this->client_db->last_query();  die('ok');
        
		if($result){
			return $query->result_array();
		}
		else{
			return array();
		}	
	}
	function GetDepartment()
	{  
		$sql="SELECT 
			t1.id,
			t1.category_name,
			t2.kpi_target_by,
			t2.kpi_target_by_id,
			t2.kpi_ids,
			t2.kpi_names 
			FROM category AS t1 
			LEFT JOIN kpi_setting AS t2 ON t1.id=t2.kpi_target_by_id AND  t2.kpi_target_by='D'
			WHERE t1.is_deleted='N' ORDER BY t1.id ";
		$query = $this->client_db->query($sql);
		$arr = array();
		if($query){
			if($query->num_rows() > 0)
			{    
				foreach($query->result() as $row)
				{ 
					$arr[] = array(
									'id'=> $row->id,
									'name'=> $row->category_name,
									'kpi_target_by'=> $row->kpi_target_by,
									'kpi_target_by_id'=> $row->kpi_target_by_id,
									'kpi_ids'=> $row->kpi_ids,
									'kpi_names'=> $row->kpi_names
									);
				}
			} 
		}
		

		           
		return $arr;
	} 

	public function GetUser($list_user_or_admin='')
	{
		$sql="SELECT 
			t1.id,
			t1.name,
			t2.kpi_target_by,
			t2.kpi_target_by_id,
			t2.kpi_ids,
			t2.kpi_names
			FROM user AS t1 
			LEFT JOIN kpi_setting AS t2 ON t1.id=t2.kpi_target_by_id AND  t2.kpi_target_by='U'
			WHERE t1.status='0'  
			ORDER BY t1.id ASC ";
        $query = $this->client_db->query($sql);
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
        		
	}	

	public function GetKpiList()
	{
		$sql="SELECT 
			t1.id,
			t1.reserve_keyword,
			t1.name,
			t1.is_system_generated,
			t1.is_active,
			t1.is_deleted
			FROM kpi AS t1 
			WHERE t1.is_active='Y' AND t1.is_deleted='N'  
			ORDER BY t1.name ASC ";
        $query = $this->client_db->query($sql);
        
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function get_kpi_setting_info($kpi_target_by,$kpi_target_by_id)
	{
		$sql="SELECT 
			t1.id,
			t1.kpi_target_by,
			t1.kpi_target_by_id,
			t1.kpi_ids,
			t1.kpi_names,
			t1.created_at,
			t1.updated_at 
			FROM kpi_setting AS t1 
			WHERE t1.kpi_target_by='".$kpi_target_by."'  AND t1.kpi_target_by_id='".$kpi_target_by_id."'";
        $query = $this->client_db->query($sql);
        
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}

	}

	public function add_kpi_setting($data)
	{
		if($this->client_db->insert('kpi_setting',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function edit_kpi_setting($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('kpi_setting',$data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function delete_kpi_setting($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('kpi_setting');
		return true;
	}

	public function truncate_kpi_setting()
	{
		if($this->client_db->truncate('kpi_setting'))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}		
	}

	
	public function delete_kpi_setting_user_wise($id)
	{
		$sql="DELETE t1, t2 
			FROM kpi_setting_user_wise t1 
			LEFT JOIN 
			kpi_setting_user_wise_set_target t2 ON t1.id=t2.kpi_setting_user_wise_id 
			WHERE t1.kpi_setting_id = '".$id."'";
		 $this->client_db->query($sql);

		return true;
	}

	public function truncate_kpi_setting_user_wise()
	{
		if($this->client_db->truncate('kpi_setting_user_wise'))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	public function truncate_kpi_setting_user_wise_set_target()
	{
		if($this->client_db->truncate('kpi_setting_user_wise_set_target'))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	public function get_user_by_target($kpi_target_by,$kpi_target_by_id)
	{
		if($kpi_target_by=='F'){
			$sql="SELECT id,name FROM user WHERE functional_area_id='".$kpi_target_by_id."'";
		}
		else if($kpi_target_by=='D'){
			$sql="SELECT id,name FROM user WHERE department_id='".$kpi_target_by_id."'";
		}		
		$query = $this->client_db->query($sql);
       
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}

	}

	public function add_kpi_setting_user_wise($data)
	{
		if($this->client_db->insert('kpi_setting_user_wise',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function kpi_target_by_list($kpi_target_by)
	{
		if($kpi_target_by=='F'){
			$sql="SELECT 
			t1.id AS kpi_setting_id,
			t2.id,
			t2.name
			FROM kpi_setting AS t1 INNER JOIN functional_area AS t2 ON t1.kpi_target_by_id=t2.id 
			WHERE t2.is_deleted='N'";
			$result=$this->client_db->query($sql);
			// echo $last_query = $this->client_db->last_query();  die('ok');
			
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
		else if($kpi_target_by=='D'){
			$sql="SELECT 
			t1.id AS kpi_setting_id,
			t2.id,
			t2.category_name AS name
			FROM kpi_setting AS t1 INNER JOIN category AS t2 ON t1.kpi_target_by_id=t2.id 
			WHERE t2.is_deleted='N'";
			$result=$this->client_db->query($sql);
			// echo $last_query = $this->client_db->last_query();  die('ok');
			
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
		else if($kpi_target_by=='U'){
			$sql="SELECT 
			t1.id AS kpi_setting_id,
			t2.id,
			t2.name
			FROM kpi_setting AS t1 INNER JOIN user AS t2 ON t1.kpi_target_by_id=t2.id 
			WHERE t2.status='0'";
			$result=$this->client_db->query($sql);
			// echo $last_query = $this->client_db->last_query();  die('ok');
			
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
				
	}

	public function get_kpi_setting($id)
	{
		$sql="SELECT 
			t1.id,
			t1.kpi_target_by,
			t1.kpi_target_by_id,
			t1.kpi_ids,
			t1.kpi_names,
			t1.created_at,
			t1.updated_at 
			FROM kpi_setting AS t1 
			WHERE t1.id='".$id."'";
        $query = $this->client_db->query($sql);
        
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	public function kpi_setting_user_wise_row($kpi_setting_id,$user_id)
	{
		$sql="SELECT 
			t1.id,
			t1.kpi_setting_id,
			t1.user_id,
			t1.set_target_by,
			t1.target_threshold 
			FROM kpi_setting_user_wise AS t1 
			WHERE t1.kpi_setting_id='".$kpi_setting_id."' AND t1.user_id='".$user_id."'";
        $query = $this->client_db->query($sql);
       
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	function AddkpiSettingUserWise($data)
	{
		if($this->client_db->insert('kpi_setting_user_wise',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function EditkpiSettingUserWise($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('kpi_setting_user_wise',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}
	
	public function kpi_setting_user_wise_set_target_row($kpi_setting_user_wise_id,$kpi_id)
	{
		$sql="SELECT 
			t1.id,
			t1.kpi_setting_user_wise_id,
			t1.kpi_id,
			t1.weighted_score,
			t1.target,
			t1.min_target_threshold,
			t1.created_at,
			t1.updated_at 
			FROM kpi_setting_user_wise_set_target AS t1 
			WHERE t1.kpi_setting_user_wise_id='".$kpi_setting_user_wise_id."' AND t1.kpi_id='".$kpi_id."'";
        $query = $this->client_db->query($sql);
        
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	function AddkpiSettingUserWiseSetTarget($data)
	{
		if($this->client_db->insert('kpi_setting_user_wise_set_target',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function EditkpiSettingUserWiseSetTarget($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('kpi_setting_user_wise_set_target',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	public function kpi_setting_user_wise_by_sid_and_user_id($kpi_setting_id,$user_id)
	{
		$sql="SELECT 
			t1.id,
			t1.kpi_setting_id,
			t1.user_id,
			t1.set_target_by,
			t1.target_threshold,
			t1.target_threshold_for_x_consecutive_month,
			t1.is_apply_pip,
			t1.is_apply_pli,
			t1.monthly_salary,
			t1.pli_in,
			t2.salary AS user_salary,
			t2.salary_currency_code AS user_salary_currency_code 
			FROM kpi_setting_user_wise AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id
			WHERE t1.kpi_setting_id='".$kpi_setting_id."' AND t1.user_id='".$user_id."'";
        $query = $this->client_db->query($sql);
        
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}
	public function kpi_setting_user_wise_set_target_by_kpi_setting_user_wise_id($kpi_setting_user_wise_id)
	{
		$sql="SELECT 
			t1.id,
			t1.kpi_setting_user_wise_id,
			t1.kpi_id,
			t1.weighted_score,
			t1.target,
			t1.achieved,
			t1.min_target_threshold,
			t1.created_at,
			t1.updated_at,
			t2.name AS kpi_name,
			t2.is_system_generated AS is_kpi_system_generated 
			FROM kpi_setting_user_wise_set_target AS t1 
			INNER JOIN kpi AS t2 ON t1.kpi_id=t2.id 
			WHERE t1.kpi_setting_user_wise_id='".$kpi_setting_user_wise_id."' ORDER BY t1.id";
        $query = $this->client_db->query($sql);
        
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function GetKpis()
	{	
		
		$sql="SELECT t1.id,
				t1.reserve_keyword,
				t1.name,
				t1.info,
				t1.is_system_generated,
				t1.is_active,
				t1.is_deleted 
				FROM kpi AS t1 WHERE t1.is_deleted='N' ORDER BY t1.id";
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function Edit_key_performance_indicator($data,$id,$client_info=array())
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('kpi',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	function Add_key_performance_indicator($data)
	{
		if($this->client_db->insert('kpi',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}
	
	public function Get_key_performance_indicator($id)
	{
		$sql="SELECT t1.id,
					t1.reserve_keyword,
					t1.name,
					t1.is_system_generated,
					t1.is_active,
					t1.is_deleted
				FROM kpi AS t1 WHERE t1.id='".$id."'";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
		
	}

	public function Delete_key_performance_indicator($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('kpi');		
		return true;
	}

	public function GetIdleInfo($client_info=array())
	{	
		if($this->class_name=='cronjobs' || $this->class_name=='capture' || $this->class_name=='account' || $this->class_name=='login' || $this->class_name=='download' || $this->class_name=='preview_quotation')
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
		$this->client_db->select('is_session_expire_for_idle,idle_time');
		$this->client_db->from('company_setting');	
		$this->client_db->where('id',1);
		$result=$this->client_db->get();
		//echo $last_query = $this->client_db->last_query();  die('ok');
		// return $result ? $result->num_rows() : 0;
		if($result){
			return $result->row_array();			
		}
		else{
			return array();
		}		
	}

	public function kpi_user_list()
	{
		$sql="SELECT 
				t1.id,
				t1.name,
				t2.id AS kpi_setting_id 
				FROM user AS t1 
				INNER JOIN kpi_setting AS t2 ON t2.kpi_target_by_id=IF(t2.kpi_target_by='U',t1.id,IF(t2.kpi_target_by='F',t1.functional_area_id,t1.department_id)) 
				WHERE t1.status='0'
				ORDER BY t1.id";
		
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function only_set_kpi_user_list($tmp_u_ids_str='')
	{
		$subsql='';
		if($tmp_u_ids_str)
		{
			$subsql .=" AND t3.user_id IN ($tmp_u_ids_str)";
		}
		$sql="SELECT 
				t1.id,
				t1.name,
				t2.id AS kpi_setting_id 
				FROM user AS t1 
				INNER JOIN kpi_setting AS t2 ON t2.kpi_target_by_id=IF(t2.kpi_target_by='U',t1.id,IF(t2.kpi_target_by='F',t1.functional_area_id,t1.department_id)) 
				INNER JOIN kpi_setting_user_wise AS t3 ON t1.id=t3.user_id 
				WHERE 1=1 $subsql
				ORDER BY t1.id";
		
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function GetBusinessTypeList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		if(COUNT(t2.id)>0,'Y','N') AS is_customer_tagged
		FROM tbl_customer_business_type AS t1 
		LEFT JOIN customer AS t2 ON t1.id=t2.business_type_id	 		
		WHERE t1.is_deleted='N' GROUP BY t1.id ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
			
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}
	}

	function AddBusinessType($data)
	{
		if($this->client_db->insert('tbl_customer_business_type',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function UpdatedBusinessType($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('tbl_customer_business_type',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}
	public function DeleteBusinessType($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('tbl_customer_business_type');
		return true;
	}

	public function only_report_wise_user_list($tmp_u_ids_str='')
	{
		$subsql='';
		if($tmp_u_ids_str)
		{
			$subsql .=" AND t3.user_id IN ($tmp_u_ids_str)";
		}
		$sql="SELECT 
				t1.id,
				t1.name,
				t2.id AS kpi_setting_id 
				FROM user AS t1 
				INNER JOIN kpi_setting AS t2 ON t2.kpi_target_by_id=IF(t2.kpi_target_by='U',t1.id,IF(t2.kpi_target_by='F',t1.functional_area_id,t1.department_id)) 
				INNER JOIN kpi_setting_user_wise AS t3 ON t1.id=t3.user_id 
				INNER JOIN kpi_user_wise_report_log AS t4 ON t1.id=t4.user_id 
				WHERE 1=1 $subsql GROUP BY t3.user_id
				ORDER BY t1.id ";
		
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		
		if($query){
			return $query->result_array();	
		}
		else{
			return array();
		}
	}

	public function user_list_for_copy_kpi($to_uid='')
	{
		$subsql="";
		if($to_uid)
		{
			$subsql .=" AND t1.user_id!='".$to_uid."'";
		}
		$sql="SELECT 
			t1.id,
			t1.kpi_setting_id,
			t1.user_id,
			t1.set_target_by,
			t1.target_threshold,
			t2.name AS user_name 
			FROM kpi_setting_user_wise AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id
			WHERE  1=1 $subsql ORDER BY t2.name";
        $query = $this->client_db->query($sql);
        
		if($query){
			return $query->result_array();	
		}
		else{
			return array();
		}
	}

	public function get_user_wise_kpi_target_for_copied($uid='')
	{
		$subsql="";		
		$sql="SELECT 
			t1.id,
			t1.kpi_setting_user_wise_id,
			t1.kpi_id,
			t1.weighted_score,
			t1.target,
			t1.achieved,
			t1.min_target_threshold,
			t1.created_at,
			t1.updated_at,
			t2.target_threshold,
			t2.target_threshold_for_x_consecutive_month,
			t2.is_apply_pip,
			t2.is_apply_pli,
			t2.monthly_salary,
			t2.pli_in 
			FROM kpi_setting_user_wise_set_target AS t1 
			INNER JOIN kpi_setting_user_wise AS t2 ON t1.kpi_setting_user_wise_id=t2.id
			WHERE  1=1 AND t2.user_id='".$uid."' ORDER BY t1.id";
        $query = $this->client_db->query($sql);
        
		if($query){
			return $query->result_array();	
		}
		else{
			return array();
		}
	}

	public function GetWhatsappServiceProviderList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.created_on 
		FROM whatsapp_service_provider AS t1 		
		WHERE 1 ORDER BY t1.id";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();		
		}
		else{
			return array();
		}
	}

	public function GetWhatsappApiList()
	{
		$sql="SELECT 
		t1.id,
		t1.sender,
		t1.apikey,
		t1.name 
		FROM whatsapp_api AS t1 		
		WHERE 1 ORDER BY t1.name";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}	
	}

	public function GetWhatsappApi($id)
	{
		$sql="SELECT 
		t1.id,
		t1.sender,
		t1.apikey,
		t1.name 
		FROM whatsapp_api AS t1 		
		WHERE t1.id=$id";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row_array();	
		}
		else{
			return array();
		}		
	}

	public function GetWhatsappTemplateList($api_id='')
	{
		$subsql='';
		if($api_id){
			$subsql .=" AND t1.whatsapp_api_id=$api_id";
		}
		$sql="SELECT 
		t1.id,
		t1.whatsapp_api_id,
		t1.template_id,
		t1.name,
		t1.text 
		FROM whatsapp_template AS t1 		
		WHERE 1 $subsql ORDER BY t1.name";
		$result=$this->client_db->query($sql);
		// echo $last_query = $this->client_db->last_query();  die('ok');
		
		if($result){
			return $result->result();		
		}
		else{
			return (object)array();
		}	
	}

	public function GetWhatsappTemplate($id='')
	{
		$subsql='';
		if($id){
			$subsql .=" AND t1.id=$id";
		}
		$sql="SELECT 
		t1.id,
		t1.whatsapp_api_id,
		t1.template_id,
		t1.name,
		t1.template_variable,
		t1.text,
		t2.sender AS api_sender,
		t2.apikey AS api_apikey,
		t2.name AS api_name
		FROM whatsapp_template AS t1 
		INNER JOIN whatsapp_api AS t2 ON t1.whatsapp_api_id=t2.id		
		WHERE 1 $subsql";
		$result=$this->client_db->query($sql);
		// echo $last_query = $this->client_db->last_query();  die('ok');
			
		if($result){
			return $result->row();		
		}
		else{
			return (object)array();
		}	
	}

	function UpdateWhatsappTemplate($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('company_setting',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function GetC2cServiceProviderList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.c2c_api_dial_url,
		t1.c2c_api_userid,
		t1.c2c_api_password,
		t1.c2c_api_client_name,
		t1.c2c_token
		FROM c2c_service_provider AS t1 		
		WHERE t1.is_active='Y' ORDER BY t1.id";
		$result=$this->client_db->query($sql);
			
		if($result){
			return $result->result_array();		
		}
		else{
			return array();
		}
	}

	public function GetC2cCredentialsDetailsById($id)
	{
		$sql="SELECT 
		t1.id,
		t1.c2c_service_provider_id,
		t1.user_id,
		t1.office_no,
		t1.caller_name,
		t1.mobile,
		t1.role,
		t1.escalation,
		t1.created_at,
		t1.updated_at,
		t2.name AS service_provider_name,
		t2.c2c_api_dial_url,
		t2.c2c_api_userid,
		t2.c2c_api_password,
		t2.c2c_api_client_name,
		t2.c2c_token,
		t2.is_active
		FROM c2c_settings AS t1 
		INNER JOIN c2c_service_provider AS t2 ON t1.c2c_service_provider_id=t2.id
		WHERE t1.id='".$id."'";
		$result=$this->client_db->query($sql);
			
		if($result){
			if($result->num_rows())
			{
				return $result->row_array();
			}
			else
			{
				return array();
			}	
		}
		else{
			return array();
		}

		
				
	}

	public function GetEmployeeTypeList()
	{
		$sql="SELECT 
		t1.id,
		t1.name,
		t1.sort_order,
		t1.is_active
		FROM employee_type AS t1 	 		
		WHERE t1.is_deleted='N' ORDER BY t1.sort_order ";
		$result=$this->client_db->query($sql);
				
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}
	}

	function UpdatedEmployeeType($user_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('employee_type',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function DeleteEmployeeType($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('employee_type');
		return true;
	}
	function AddEmployeeType($data)
	{
		if($this->client_db->insert('employee_type',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	
	public function get_last_sort_order()
	{
		$sql="SELECT MAX(sort_order) AS max_sort_order	FROM employee_type WHERE is_deleted='N'";
		$result=$this->client_db->query($sql);
		
		if($result){
			if($result->num_rows())
			{
				return $result->row_array()['max_sort_order'];	
			}
			else
			{
				return 0;
			}
		}
		else{
			return 0;
		}
	}

	function customer_wise_contact_person_list($customer_id='')
	{
		$subsql="";		
		$sql="SELECT t1.id,
		t1.customer_id,
		t1.name,
		t1.email,
		t1.mobile,
		t1.dob,
		t1.doa,
		t1.designation,
		t1.created_at,
		t1.updated_at 
		FROM customer_contact_person AS t1 WHERE t1.customer_id='".$customer_id."' ORDER BY t1.name";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
		
	}

	function GetBranchList()
	{		
		$sql="select 
		t1.id,
		t1.company_setting_id,
		t1.name,
		t1.contact_person,
		t1.email,
		t1.mobile,
		t1.address,
		t1.country_id,
		t1.state_id,
		t1.city_id,
		t1.pin,
		t1.gst,
		t1.created_at,
		t1.updated_at,
		t1.is_active,
		if(t1.company_setting_id,'Main Branch','') AS cs_name,
		(select contact_person from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_contact_person,
		(select email1 from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_email,
		(select mobile1 from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_mobile,
		(select address from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_address,
		(select country_id from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_country_id,
		(select state_id from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_state_id,
		(select city_id from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_city_id,
		(select pin from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_pin,
		(select gst from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_gst,
		countries.name AS country_name,
		states.name AS state_name,
		cities.name AS city_name
		from company_branch AS t1 
		LEFT JOIN countries ON if(t1.country_id,t1.country_id,(select country_id from company_setting t2 WHERE t1.company_setting_id=t2.id))=countries.id 
		LEFT JOIN states ON if(t1.state_id,t1.state_id,(select state_id from company_setting t2 WHERE t1.company_setting_id=t2.id))=states.id  
		LEFT JOIN cities ON if(t1.city_id,t1.city_id,(select city_id from company_setting t2 WHERE t1.company_setting_id=t2.id))=cities.id WHERE t1.is_deleted='N'";
		// echo $sql; die();
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function branch_edit($data,$id)
	{		
		$this->client_db->where('id',$id);
		if($this->client_db->update('company_branch',$data))
		{			
			return true;
		}
		else
		{
			return false;
		}		

	}

	function branch_add($data)
	{

		if($this->client_db->insert('company_branch',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}

	public function branch_delete($id)
	{
		$this->client_db->where('id', $id);
    	$this->client_db->delete('company_branch');
	}

	public function GetCompanyDataDislike($client_info=array())
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
		
		$this->client_db->select('
			company_setting.name,
			company_setting.logo,
			cities.name as city_name,
			countries.name as country_name');
		$this->client_db->from('company_setting');
		$this->client_db->join('countries', 'countries.id = company_setting.country_id','left');		
		$this->client_db->join('cities', 'cities.id = company_setting.city_id', 'left');		
		$this->client_db->where('company_setting.id',1);
		$result=$this->client_db->get();
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
		
	}

	public function GetDefaultCurrency($client_info=array())
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
		$this->client_db->select('company_setting.default_currency,currency.name AS default_currency_name,currency.code AS default_currency_code');
		$this->client_db->from('company_setting');		
		$this->client_db->join('currency', 'currency.id = company_setting.default_currency', 'left');
		$this->client_db->where('company_setting.id',1);
		$result=$this->client_db->get();
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	function GetFbFieldKeywordList()
	{
		$sql="SELECT id,keyword,display_name FROM fb_field_keywords WHERE is_deleted='N' ORDER BY sort";		
		$result=$this->client_db->query($sql);		
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function GetFbPageList()
	{
		$sql="SELECT id,fb_id,fb_name,fb_token FROM fb_pages ORDER BY fb_name";		
		$result=$this->client_db->query($sql);		
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function GetFbPageWiseFormList($fb_page_id='')
	{
		if($fb_page_id){
			$sql="SELECT id,fb_page_id,form_id,form_name FROM fb_form WHERE fb_page_id='".$fb_page_id."' ORDER BY form_name";		
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

	function GetFbFormWiseFieldList($fb_form_id='')
	{
		if($fb_form_id){
			$sql="SELECT 
			t1.fb_page_id,
			t1.fb_page_access_token,
			t1.fb_form_id,
			t2.fb_field_name,
			t2.system_field_name_keyword FROM fb_form_wise_lead_field_set AS t1 
			INNER JOIN fb_form_wise_lead_field_set_details AS t2 ON t1.id=t2.form_wise_lead_field_set_id 
			WHERE t1.fb_form_id='".$fb_form_id."' ORDER BY t2.id";	
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

	function truncate_page_table()
	{
		$this->client_db->truncate('fb_pages');
	}
	function truncate_form_table()
	{
		$this->client_db->truncate('fb_form');
	}
	function truncate_lead_field_set_table()
	{
		$this->client_db->truncate('fb_form_wise_lead_field_set');
		$this->client_db->truncate('fb_form_wise_lead_field_set_details');
	}

	function AddPage($data)
	{
		if($this->client_db->insert('fb_pages',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function AddForm($data)
	{
		if($this->client_db->insert('fb_form',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function AddFormWiseLeadField($data)
	{
		if($this->client_db->insert('fb_form_wise_lead_field_set',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}
	function AddFormWiseLeadFieldDetail($data)
	{
		if($this->client_db->insert('fb_form_wise_lead_field_set_details',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function GetFbFieldSet($id='')
	{
		if($id){
			$sql="SELECT 
			t1.fb_page_id,
			t1.fb_page_access_token,
			t1.fb_form_id,
			GROUP_CONCAT(t2.fb_field_name) AS fb_field_name_str,
			GROUP_CONCAT(t2.system_field_name_keyword) AS system_field_name_keyword_str FROM fb_form_wise_lead_field_set AS t1 
			INNER JOIN fb_form_wise_lead_field_set_details AS t2 ON t1.id=t2.form_wise_lead_field_set_id WHERE t1.id='".$id."'
			GROUP BY t2.form_wise_lead_field_set_id";	
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

	function GetFbFormWiseLeadFieldRow($fb_form_id='')
	{
		if($fb_form_id){
			$sql="SELECT 
			t1.id,
			t1.fb_page_id,
			t1.fb_page_access_token,
			t1.fb_form_id FROM fb_form_wise_lead_field_set AS t1 
			WHERE t1.fb_form_id='".$fb_form_id."'";	
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

	public function delete_lead_field_set_details_table($id)
	{
		$this->client_db->where('form_wise_lead_field_set_id', $id);
		$this->client_db->delete('fb_form_wise_lead_field_set_details');
		return true;
	}

	function GetFbFormFieldSetList()
	{
		$sql="SELECT 
		t1.id,
		t1.fb_page_id,
		t1.fb_page_access_token,
		t1.fb_form_id,
		t1.is_default,
		GROUP_CONCAT(t2.fb_field_name) AS fb_field_name_str,
		GROUP_CONCAT(t2.system_field_name_keyword) AS system_field_name_keyword_str,
		fb_form.form_name,
		fb_pages.fb_name AS page_name 
		FROM fb_form_wise_lead_field_set AS t1 
		INNER JOIN fb_form_wise_lead_field_set_details AS t2 ON t1.id=t2.form_wise_lead_field_set_id 
		INNER JOIN fb_form ON fb_form.form_id=t1.fb_form_id 
		INNER JOIN fb_pages ON fb_pages.fb_id=t1.fb_page_id
		GROUP BY t2.form_wise_lead_field_set_id";	
		$result=$this->client_db->query($sql);		
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function delete_page_wise_form($fb_page_id)
	{
		$this->client_db->where('fb_page_id', $fb_page_id);
		$this->client_db->delete('fb_form');
		return true;
	}

	public function delete_form_wise_lead_field_set($id)
	{
		if($id){
			$this->client_db->where('id', $id);
			$this->client_db->delete('fb_form_wise_lead_field_set');

			$this->client_db->where('form_wise_lead_field_set_id', $id);
			$this->client_db->delete('fb_form_wise_lead_field_set_details');
			return true;
		}
		else{
			return false;
		}		
	}

	function EditFormWiseLeadField($data,$id='')
	{
		if($id){
			$this->client_db->where('id',$id);	
		}
		if($this->client_db->update('fb_form_wise_lead_field_set',$data)){
			return true;		  
		}
		else{
			return false;
		}
	}

	function is_page_exist($fb_page_id='')
	{
		if($fb_page_id){
			$sql="SELECT id FROM fb_pages WHERE fb_id='".$fb_page_id."'";	
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

	function UpdatePage($data,$fb_id='')
	{
		$this->client_db->where('fb_id',$fb_id);
		if($this->client_db->update('fb_pages',$data)){
			return true;		  
		}
		else{
			return false;
		}	
	}

	function is_form_exist($fb_page_id='',$fb_form_id='')
	{
		if($fb_page_id!='' && $fb_form_id!=''){
			$sql="SELECT id FROM fb_form WHERE fb_page_id='".$fb_page_id."' AND form_id='".$fb_form_id."'";	
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

	function UpdateForm($data,$fb_page_id='',$fb_form_id='')
	{
		$this->client_db->where('fb_page_id',$fb_page_id);
		$this->client_db->where('form_id',$fb_form_id);
		if($this->client_db->update('fb_form',$data)){
			return true;		  
		}
		else{
			return false;
		}	
	}
}
?>