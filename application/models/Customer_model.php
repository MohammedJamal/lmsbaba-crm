<?php
class Customer_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() {
        parent::__construct();
		$this->user_arr=array();
		$this->class_name=$this->router->fetch_class();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    }

	public function GetCustomerListAll($search_data=array())
	{
		$subsql='';
		if($search_data['search_keyword']!='')
		{
			$subsql.=" AND t1.company_name LIKE '%".$search_data['search_keyword']."%'";
		}
		$sql="SELECT t1.*,COUNT(t2.id) AS lead_count,t3.name AS assigned_user_name,
			t4.name AS city_name,t5.name AS country_name,SUM(if(t2.current_stage_id=4,1,0)) AS lead_won_count 
			FROM customer AS t1 
			LEFT JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.status='1'
			LEFT JOIN user AS t3 ON t1.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id			
			WHERE t1.status='1' $subsql GROUP BY t1.id ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		// $this->client_db->from('customer');
		// $this->client_db->where('status','1');	
		// $result=$this->client_db->get();
		// return $result->result();
	}
	

	

	function CreateCustomer($data,$client_info=array())
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
		
		if($this->client_db->insert('customer',$data))
   		{	
           	$last_id=$this->client_db->insert_id();
           	if($this->class_name!='cronjobs')
			{			
				if(count($data))
				{				
					$session_data=$this->session->userdata('admin_session_data');
					$updated_by_user_id=(isset($session_data['user_id']))?$session_data['user_id']:'1';
					$history_data=array(
					'c_id'=>$last_id,
					'updated_by_user_id'=>$updated_by_user_id,
					'ip_address'=>$this->input->ip_address(),
					'comment'=>'New company created on '. datetime_db_format_to_display_format(date("Y-m-d H:i:s")),
					'created_at'=>date("Y-m-d")
					);
					$this->client_db->insert('company_update_history_log',$history_data);
					$tmp_log_id=$this->client_db->insert_id();
					foreach($data as $k=>$v)
					{
						if($v!='' && $k!='create_date' && $k!='modify_date' && $k!='status')
						{
							$updated_val='';					

							if($k=='city')
							{
								$v=get_value('name','cities','id='.$v,$client_info);	
							}
							else if($k=='state')
							{
								$v=get_value('name','states','id='.$v,$client_info);
							}
							else if($k=='country_id')
							{
								$v=get_value('name','countries','id='.$v,$client_info);
							}
							else if($k=='source_id')
							{
								$v=get_value('name','source','id='.$v,$client_info);
							}

							$history_data_log=array(
										'company_update_history_log_id'=>$tmp_log_id,
										'updated_field'=>$k,
										'updated_value'=>$v,
										'before_update_value'=>$updated_val
										);
							$this->client_db->insert('company_update_history_log_details',$history_data_log);
						}				
					}
				}			
			}
			return $last_id;
   		}
   		else
   		{
			//return $this->client_db->last_query();
          return false;
   		}
	}
	
	public function GetCustomerData($id,$client_info=array())
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
	
	public function GetCustomerDataByEmail($email)
	{

		$this->client_db->from('customer');
		
		$this->client_db->where("email LIKE '%$email%'");

		//$this->client_db->where('status','1');	

		$result=$this->client_db->get();

		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}
	
	public function GetCustomerListByEmail($email)
	{

		$this->client_db->select('customer.*,lead.id as lead_id,lead.title as lead_title,lead.assigned_user_id as assigned_user_id,countries.name as country_name,states.name as state_name,cities.name as city_name,user.name as assigned_user_name');
		
		$this->client_db->from('customer');	
		
		$this->client_db->join('lead', 'lead.customer_id = customer.id','inner');
		$this->client_db->join('user', 'lead.assigned_user_id = user.id','inner');
		$this->client_db->join('countries', 'countries.id = customer.country_id', 'left');
		$this->client_db->join('states', 'states.id = customer.state', 'left');
		$this->client_db->join('cities', 'cities.id = customer.city', 'left');
		
		$this->client_db->where("customer.email",$email);

		//$this->client_db->where('customer.status','1');

		$result=$this->client_db->get();

		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}
	
	public function GetCustomerDataByMobile($mobile)
	{

		$this->client_db->from('customer');

		$this->client_db->where("mobile LIKE '%$mobile%'");

		//$this->client_db->where('status','1');	

		$result=$this->client_db->get();

		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}
	
	
	
	public function GetCustomerDataByMobileOrEmail($email,$mobile)
	{

		$this->client_db->from('customer');

		$this->client_db->where("email",$email);
		$this->client_db->or_where("alt_email",$email);
		$this->client_db->or_where("mobile",$mobile);
		$this->client_db->or_where("office_phone",$mobile);
		//$this->client_db->where('status','1');	

		$result=$this->client_db->get();

		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}
	
	public function GetCustomerDataByLead($email,$mobile)
	{

		$this->client_db->select('customer.*,lead.id as lead_id,lead.title as lead_title');
		
		$this->client_db->from('customer');	
		
		$this->client_db->join('lead', 'lead.customer_id = customer.id','inner');
		

		$this->client_db->where("customer.email",$email);
		$this->client_db->or_where("customer.alt_email",$email);
		if($mobile!='')
		{
			$this->client_db->where("customer.mobile",$mobile);
			//$this->client_db->or_where("customer.office_phone",$mobile);
		}
		
		//$this->client_db->where('status','1');	

		$result=$this->client_db->get();

		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}

	function get_company_update_log_details_by_logid($log_id)
	{
		$sql="SELECT * FROM company_update_history_log_details WHERE company_update_history_log_id='".$log_id."'";
		$result=$this->client_db->query($sql);
		//return $result->result_array();

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	function UpdateCustomer($data,$id,$client_info=array())
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
		if($this->class_name!='cronjobs')
		{		
			$tmp_log_id=0;
			
			if(count($data))
			{
				//print_r($data); die();
				$i=1;
				foreach($data as $k=>$v)
				{
					if($v!='' && $k!='modify_date')
					{	
						$updated_val=get_value($k,"customer","id=".$id,$client_info);
						//echo strtolower(preg_replace('/\s+/', '', $updated_val)).'!='.strtolower(preg_replace('/\s+/', '', $v));die();
						if(strtolower(preg_replace('/\s+/', '', $updated_val))!=strtolower(preg_replace('/\s+/', '', $v)))
						{						
							if($i==1)
							{
								$session_data=$this->session->userdata('admin_session_data');
			   					$updated_by_user_id=(isset($session_data['user_id']))?$session_data['user_id']:'1';
								$history_data=array(
										'c_id'=>$id,
										'updated_by_user_id'=>$updated_by_user_id,
										'ip_address'=>$this->input->ip_address(),
										'comment'=>'',
										'created_at'=>date("Y-m-d")
										);
								$this->client_db->insert('company_update_history_log',$history_data);
								$tmp_log_id=$this->client_db->insert_id();
								$i++;
							}
							
							if($k=='city')
							{
								$v=get_value('name','cities','id='.$v,$client_info);
								if($updated_val>0)
								{
									$updated_val=get_value('name','cities','id='.$updated_val,$client_info);
								}
							}
							else if($k=='state')
							{
								$v=get_value('name','states','id='.$v,$client_info);
								if($updated_val>0)
								{
									$updated_val=get_value('name','states','id='.$updated_val,$client_info);
								}
							}
							else if($k=='country_id')
							{	
								$v=get_value('name','countries','id='.$v,$client_info);
								if($updated_val>0)
								{
									$updated_val=get_value('name','countries','id='.$updated_val,$client_info);
								}
							}
							else if($k=='source_id')
							{	
								$v=get_value('name','source','id='.$v,$client_info);
								if($updated_val>0)
								{
									$updated_val=get_value('name','source','id='.$updated_val,$client_info);
								}
							}

							$history_data_log=array(
										'company_update_history_log_id'=>$tmp_log_id,
										'updated_field'=>$k,
										'updated_value'=>$v,
										'before_update_value'=>$updated_val
										);
							//print_r($history_data_log); 
							$this->client_db->insert('company_update_history_log_details',$history_data_log);
						}
					}				
				}
			}		
		}
		
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

	function DeleteCustomer($id)
	{
		$data=array('status'=>'2');
		$this->client_db->where('id',$id);

		if($this->client_db->update('customer',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	function get_company_detail($id,$client_info=array())
	{		
		if($this->class_name=='cronjobs' || $this->class_name=='capture' || $this->class_name=='capture' || $this->class_name=='rest_meeting')
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

	function company_search_to_add_lead($mobile='',$email='',$client_info=array())
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
		if($mobile!='' && $email=='')
		{
			$subsql .=" AND t1.mobile='".$mobile."'";
		}

		if($email!='' && $mobile=='')
		{
			$subsql .=" AND t1.email='".$email."'";
		}

		if($mobile!='' && $email!='')
		{
			$subsql .=" AND (t1.mobile='".$mobile."' OR t1.email='".$email."')";
		}


		$sql="SELECT 
		t1.*,
		COUNT(t2.id) AS lead_count 
		FROM customer AS t1 
		LEFT JOIN lead AS t2 ON t1.id=t2.customer_id 
		WHERE t1.status='1' $subsql GROUP BY t2.customer_id";
		$result=$this->client_db->query($sql);
		// echo $last_query = $this->client_db->last_query();die();		
		if($result){
			return $result->result_array();;
		}
		else{
			return array();
		}
	}

	function company_search_to_add_lead_by_id($id='',$client_info=array())
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
		if($id!='')
		{
			$subsql .=" AND t1.id='".$id."'";
		}
		$sql="SELECT t1.*,COUNT(t2.id) AS lead_count 
		FROM customer AS t1 
				LEFT JOIN lead AS t2 ON t1.id=t2.customer_id 
			WHERE t1.status='1' $subsql GROUP BY t2.customer_id";
		$result=$this->client_db->query($sql);
		//return $result->result_array();

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}


	// ========================================================================
	// DASHBOARD SUMMARY REPORT
	// --------------------------------
	// COMPANY COUNT


	// --------------------------------
	public function get_company_count()
	{
		$sql = "SELECT count(*) AS total_active, 
				sum(if(status='1',0,1)) AS total_inactive 
				FROM customer";
        $query  = $this->client_db->query($sql);
        // return $query->num_rows();
        return $query->row_array();
	}

	// ---------------------------
	// Customer Contacts
	public function customer_contacts_summary($user_type,$user_id,$to_date)
	{
		$today_date  = date('Y-m-d');
		$subsql = "";		

		$sql = "SELECT c.*,ct.name AS city_name,st.name AS state_name,cnt.name AS country_name 
				FROM customer AS c 
				LEFT JOIN cities AS ct ON ct.id=c.city
				LEFT JOIN states AS st ON st.id=c.state
				LEFT JOIN countries AS cnt ON cnt.id=c.country_id
				WHERE c.status='1' $subsql ORDER BY c.create_date DESC LIMIT 0,5";

		$result=$this->client_db->query($sql);
		//return $result->result_array();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	// Customer Contacts
	// ---------------------------

	// DASHBOARD SUMMARY REPORT
	// ========================================================================


	public function get_company_history_log($cid)
	{
		$subsql='';
		if($cid)
		{
			$subsql=' AND t1.c_id='.$cid;
		}
		$sql = "SELECT t1.id,
				t1.history_type,
				t1.c_id,
				t1.updated_by_user_id,
				t1.ip_address,
				t1.comment,
				t1.created_at,
				t2.name AS updated_by_user_name 
				FROM company_update_history_log AS t1 
				INNER JOIN user AS t2 ON t1.updated_by_user_id=t2.id 
				WHERE 1=1 $subsql ORDER BY id DESC";
        $query  = $this->client_db->query($sql);
        $result=array();
        if($query->num_rows() > 0)
		{
		  foreach ($query->result() as $row) 
		  { 
			if($row->history_type=='T')
			{
				$detail=$this->get_company_history_log_details($row->id);
			}
			else if($row->history_type=='E')
			{
				$detail=$this->get_company_history_log_email_details($row->id);
			}
			
		  	$result[] = array(
		  	  'id'=> $row->id,
			  'history_type'=> $row->history_type,
              'c_id'=> $row->c_id,
              'updated_by_user_id'=>$row->updated_by_user_id, 
              'updated_by_user_name'=>$row->updated_by_user_name,                    
              'ip_address'=> $row->ip_address,
              'comment'=> $row->comment,
              'created_at'=> $row->created_at,
              'details'=> $detail
              );
		  }
		}
		return $result;
	}

	public function get_company_history_log_details($id)
	{
		$sql = "SELECT * 
				FROM company_update_history_log_details 
				WHERE company_update_history_log_id='".$id."' ORDER BY id";

		$result=$this->client_db->query($sql);
		//return $result->result_array();

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	
	public function get_company_history_log_email_details($id)
	{
		$sql = "SELECT * 
				FROM company_update_history_log_email_details 
				WHERE company_update_history_log_id='".$id."' ORDER BY id";

		$result=$this->client_db->query($sql);
		//return $result->result_array();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	
	public function get_company_wise_lead($id,$filter)
	{
		$subsql="";
		if($filter!='')
		{
			if($filter=='deal_won')
			{
				$subsql .=" AND lead.current_stage_id='4'";
			}
		}
		
		$sql="SELECT 
			COUNT(lead_opportunity.lead_id) AS proposal,
			lead.id,
			lead.title,
			lead.customer_id,
			lead.current_stage,
			lead.current_status,
			lead.buying_requirement,
			lead.enquiry_date,
			user.name AS user_name,
			cus.first_name AS cus_first_name,
			cus.last_name AS cus_last_name,
			cus.mobile AS cus_mobile,
			cus.email AS cus_email,
			cus.company_name AS cus_company_name,
			source.name AS source_name			
			FROM lead INNER JOIN customer AS cus ON cus.id=lead.customer_id 
			LEFT JOIN source ON source.id=lead.source_id 
			LEFT JOIN user ON user.id=lead.assigned_user_id 
			LEFT JOIN lead_opportunity ON lead_opportunity.lead_id=lead.id WHERE lead.status='1' AND lead.customer_id='".$id."' $subsql GROUP BY lead.id ORDER BY lead.id DESC ";
		$result=$this->client_db->query($sql);
		//return $result->result_array();

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	
	function company_history_add($data,$client_info=array())
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

		if($this->client_db->insert('company_update_history_log',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	function company_history_email_detail_add($data,$client_info=array())
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

		if($this->client_db->insert('company_update_history_log_email_details',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}

	public function searchCompany($search_data){
		$sql="SELECT t1.*,COUNT(t2.id) AS lead_count,t3.name AS assigned_user_name,
			t4.name AS city_name,t5.name AS country_name,SUM(if(t2.current_stage_id=4,1,0)) AS lead_won_count 
			FROM customer AS t1 
			LEFT JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.status='1'
			LEFT JOIN user AS t3 ON t2.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id			
			WHERE t1.status='1' AND t1.company_name LIKE '%".$search_data."%' GROUP BY t1.id ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function getCompanies($type){
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		// $tmp_u_ids_str=implode(",", $tmp_u_ids);

		if($type=='count'){
			$this->client_db->where(['status'=>'1']);
			$this->client_db->where_in('assigned_user_id', $tmp_u_ids );
			$result = $this->client_db->get('customer');
			// echo $last_query = $this->client_db->last_query();die();
			return $result->num_rows();
		}
		$sql="SELECT t1.*,COUNT(t2.id) AS lead_count,t3.name AS assigned_user_name,
			t4.name AS city_name,t5.name AS country_name,SUM(if(t2.current_stage_id=4,1,0)) AS lead_won_count 
			FROM customer AS t1 
			LEFT JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.status='1'
			LEFT JOIN user AS t3 ON t1.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id			
			WHERE t1.status='1' GROUP BY t1.id ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function getPayingCompanies($type){
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		if($type=='count'){

			$sql="SELECT t1.* FROM customer AS t1 
			INNER JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.current_stage='DEAL WON' AND t2.status='1' AND t1.assigned_user_id IN ($tmp_u_ids_str)
			WHERE t1.status='1' GROUP BY t1.id ORDER BY t1.id DESC";
			$result=$this->client_db->query($sql);
			if(is_object($result)){
				return $result->num_rows();
			}
			else{
				return 0;
			}
			
		}else{
			$sql="SELECT t1.*,COUNT(t2.id) AS lead_count,t3.name AS assigned_user_name,
			t4.name AS city_name,t5.name AS country_name,SUM(if(t2.current_stage_id=4,1,0)) AS lead_won_count 
			FROM customer AS t1 
			INNER JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.current_stage='DEAL WON' AND t2.status='1'
			LEFT JOIN user AS t3 ON t1.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id			
			WHERE t1.status='1' GROUP BY t1.id ORDER BY t1.id DESC";
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

	public function getDomesticCompanies($type){
		$country_id=101;
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		//$tmp_u_ids_str=implode(",", $tmp_u_ids);
		
		$this->client_db->where(['sortname'=>'IN', 'name'=>'India', 'phonecode'=>91]);

		$result = $this->client_db->get('countries');
		if($result->num_rows()){
			$country_id = $result->row()->id;
		}else{
			if($type=='count'){
				return 0;
			}
			return array();
		}
		if($type=='count'){
			$this->client_db->where(['country_id'=>$country_id, 'status'=>'1']);
			$this->client_db->where_in('assigned_user_id', $tmp_u_ids );
			$result = $this->client_db->get('customer');
			return $result->num_rows();
		}
		$sql="SELECT t1.*,COUNT(t2.id) AS lead_count,t3.name AS assigned_user_name,
			t4.name AS city_name,t5.name AS country_name,SUM(if(t2.current_stage_id=4,1,0)) AS lead_won_count 
			FROM customer AS t1 
			LEFT JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.status='1'
			LEFT JOIN user AS t3 ON t1.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id			
			WHERE t1.status='1' AND t1.country_id=$country_id GROUP BY t1.id ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function getForeignCompanies($type){
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		//$tmp_u_ids_str=implode(",", $tmp_u_ids);

		$this->client_db->where(['sortname'=>'IN', 'name'=>'India', 'phonecode'=>91]);
		$result = $this->client_db->get('countries');
		if($result->num_rows()){
			$country_id = $result->row()->id;
		}else{
			if($type=='count'){
				return 0;
			}
			return array();
		}
		if($type=='count'){
			$this->client_db->where(['country_id!='=>$country_id,'status'=>'1']);
			$this->client_db->where_in('assigned_user_id', $tmp_u_ids );
			$result = $this->client_db->get('customer');
			return $result->num_rows();
		}
		$sql="SELECT t1.*,COUNT(t2.id) AS lead_count,t3.name AS assigned_user_name,
			t4.name AS city_name,t5.name AS country_name,SUM(if(t2.current_stage_id=4,1,0)) AS lead_won_count 
			FROM customer AS t1 
			LEFT JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.status='1'
			LEFT JOIN user AS t3 ON t1.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id			
			WHERE t1.status='1' AND t1.country_id!=$country_id GROUP BY t1.id ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	function assign_to_update()
	{
		$sql="SELECT * FROM lead GROUP BY customer_id ORDER BY id";
		$query=$this->client_db->query($sql);
		$i=0;
		if($query->num_rows() > 0)
		{
			$i=1;
		  foreach ($query->result() as $row) 
		  { 
		  	//print_r($row);
		  	$this->client_db->where('id',$row->customer_id);
		  	$post_data=array('assigned_user_id'=>$row->assigned_user_id);
			if($this->client_db->update('customer',$post_data))
			{			
				$this->client_db->where('customer_id',$row->customer_id);
				$post_lead_data=array('assigned_user_id'=>$row->assigned_user_id);
				$this->client_db->update('lead',$post_lead_data);
				$i++;
			}
			else
			{
				
			}	
		  }
		}
		return $i;
	}

	function get_decision($arg=array(),$client_info=array())
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
											return array('msg'=>'SENDEREMAIL and MOB both missing','customer_id'=>'','status'=>FALSE);
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
							else
							{
								return array('msg'=>'SENDEREMAIL and MOB both missing','customer_id'=>'','status'=>FALSE);
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
								return array('msg'=>'SENDEREMAIL and MOB both missing','customer_id'=>'','status'=>FALSE);
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
			else
			{
				return array('msg'=>'QUERY_ID missing','customer_id'=>'','status'=>FALSE);
			}
			
		}
		else
		{
			return array('msg'=>'QUERY_ID missing','customer_id'=>'','status'=>FALSE);
		}					
	}


	function get_decision_website($arg=array(),$client_info=array())
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
								return array('msg'=>'SENDEREMAIL and MOB both missing','customer_id'=>'','status'=>FALSE);
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
					return array('msg'=>'SENDEREMAIL and MOB both missing','customer_id'=>'','status'=>FALSE);
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
					return array('msg'=>'SENDEREMAIL and MOB both missing','customer_id'=>'','status'=>FALSE);
				}	
													
			}		
		}
		else
		{
			return array('msg'=>'SENDEREMAIL and MOB both missing','customer_id'=>'','status'=>FALSE);
		}					
	}

	// AJAX SEARCH START
    function get_list_count($argument=NULL)
    {
        $subsql = ''; 
		$subsqlInner='';
        // ---------------------------------------
        // SEARCH VALUE
        if($argument['filter_search_str']!='')
        {
            $subsql.=" AND (t1.company_name LIKE '%".$argument['filter_search_str']."%' || t1.contact_person LIKE '%".$argument['filter_search_str']."%' || t1.email LIKE '%".$argument['filter_search_str']."%' || t1.mobile LIKE '%".$argument['filter_search_str']."%' || st.name LIKE '%".$argument['filter_search_str']."%' || t4.name LIKE '%".$argument['filter_search_str']."%')";
			//$subsql .= " AND  (t1.company_name LIKE '".$argument['filter_search_str']."%' OR t1.id='".$argument['filter_search_str']."')";
        }

        if($argument['filter_created_from_date']!='' && $argument['filter_created_to_date']!='')
		{			
			
			$from = date_display_format_to_db_format($argument['filter_created_from_date']);  
			$to = date_display_format_to_db_format($argument['filter_created_to_date']);  
			$subsql.=" AND (t1.create_date BETWEEN '".$from."' AND '".$to."') ";
		}

		if($argument['assigned_user'])
        {
        	$subsql .= ' AND t1.assigned_user_id IN ('.$argument['assigned_user'].')';   
        }  

        if($argument['filter_by_company_available_for']!='')
		{		
			$is_export_filter='';
			$is_domestic_filter='';
			if($argument['filter_by_company_available_for']=='E')
			{
				$is_export_filter='Y';
			}
			else if($argument['filter_by_company_available_for']=='D')
			{				
				$is_domestic_filter='Y';
			}
			else
			{
				$lead_applicable_for_arr=explode(",",$argument['filter_by_company_available_for']);
				$is_export_filter='Y';
				$is_domestic_filter='Y';
			}
			
			$company=get_company_profile();
			$company_country_id=$company['country_id'];				
			if($is_export_filter=='Y' && $is_domestic_filter=='')
			{
				$subsql.=" AND t1.country_id !='".$company_country_id."'";
			}
			
			if($is_export_filter=='' && $is_domestic_filter=='Y')
			{
				$subsql.=" AND t1.country_id ='".$company_country_id."'";
			}
		}

		if($argument['filter_by_is_available_company_name'])
        {
        	if($argument['filter_by_is_available_company_name']=='Y')
        	{
        		$subsql .= ' AND (t1.company_name IS NOT NULL || t1.company_name!="")';
        	}
        	else if($argument['filter_by_is_available_company_name']=='N')
        	{
        		$subsql .= ' AND (t1.company_name IS NULL || t1.company_name="")';
        	}        	   
        }  

        if($argument['filter_by_is_available_email'])
        {
        	if($argument['filter_by_is_available_email']=='Y')
        	{
        		$subsql .= ' AND (t1.email IS NOT NULL || t1.email!="")';
        	}
        	else if($argument['filter_by_is_available_email']=='N')
        	{
        		$subsql .= ' AND (t1.email IS NULL || t1.email="")';
        	}        	   
        }  

        if($argument['filter_by_is_available_phone'])
        {
        	if($argument['filter_by_is_available_phone']=='Y')
        	{
        		$subsql .= ' AND (t1.mobile!="")';
        	}
        	else if($argument['filter_by_is_available_phone']=='N')
        	{
        		$subsql .= ' AND (t1.mobile IS NULL || t1.mobile="")';
        	}        	   
        } 

        if($argument['filter_last_contacted'])
        {
        	if($argument['filter_last_contacted']=='custom_date' && $argument['filter_last_contacted_custom_date']!='')
        	{
        		$days_ago = date_display_format_to_db_format($argument['filter_last_contacted_custom_date']);
        	}
        	else
        	{
        		$days_ago = date('Y-m-d', strtotime('-'.$argument['filter_last_contacted'].' days', strtotime(date('Y-m-d'))));
        	}    
        	$subsql .= " AND t1.last_mail_sent<='".$days_ago."'" ;    	   
        } 
        
        if($argument['filter_country'])
        {        	    
        	$subsql .= " AND t1.country_id IN ('".$argument['filter_country']."')";
        }
		
		$having="";
		if($argument['filter_company_type'])
        { 
			if(strtoupper($argument['filter_company_type'])=='PC')
			{
				$having .= " HAVING SUM(if(t2.current_stage_id=4,1,0)) >0";
			}
			else if(strtoupper($argument['filter_company_type'])=='FC')
			{				
				$having .= " HAVING SUM(if(t2.current_stage_id=4,1,0))=0";
			}
			else if(strtoupper($argument['filter_company_type'])=='BC')
			{				
				$subsql.=" AND t1.is_blacklist='Y'";			
			}
			else
			{				
				
			}        	
        }

        if($argument['source_ids']!='')
		{			
			$subsql.=" AND t1.source_id IN (".$argument['source_ids'].")";
		}

		if($argument['business_type_id'])
        {        	    
        	$subsql .= " AND t1.business_type_id='".$argument['business_type_id']."'";
        }
        // SEARCH VALUE
        // ---------------------------------------
            
		$sql="SELECT 
        		t1.id,
        		t1.status
			FROM customer AS t1 
			LEFT JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.status='1'
			LEFT JOIN user AS t3 ON t1.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN states AS st ON st.id=t1.state
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id			
			WHERE t1.status='1' $subsql GROUP BY t1.id $having"; 

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
        $order_by_str = " ORDER BY  t1.id DESC ";
        if($argument['filter_sort_by']!='')
        {			
			$filter_sort_by_arr=explode("@",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			if($field_name=='leads' || $field_name=='orders' || $field_name=='assigned_user_id' || $field_name=='country_id')
			{
				if($field_name=='leads')
				{
					$order_by_str = " ORDER BY  COUNT(t2.id) ".$order_by;
				}
				else if($field_name=='orders')
				{
					$order_by_str = " ORDER BY  SUM(if(t2.current_stage_id=4,1,0)) ".$order_by;
				}	
				else if($field_name=='assigned_user_id')
				{
					$order_by_str = " ORDER BY  t3.name ".$order_by;
				}
				else if($field_name=='country_id')
				{
					$order_by_str = " ORDER BY  t5.name ".$order_by;
				}
				else if($field_name=='state')
				{
					$order_by_str = " ORDER BY  st.name ".$order_by;
				}
				else if($field_name=='city')
				{
					$order_by_str = " ORDER BY  t4.name ".$order_by;
				}				
			}
			else
			{
				$order_by_str = " ORDER BY  t1.$field_name ".$order_by;
			}						
        }

        // ---------------------------------------
        // SEARCH VALUE
        if($argument['filter_search_str']!='')
        {
            $subsql.=" AND (t1.company_name LIKE '%".$argument['filter_search_str']."%' || t1.contact_person LIKE '%".$argument['filter_search_str']."%' || t1.email LIKE '%".$argument['filter_search_str']."%' || t1.mobile LIKE '%".$argument['filter_search_str']."%' || st.name LIKE '%".$argument['filter_search_str']."%' || t4.name LIKE '%".$argument['filter_search_str']."%')";
			//$subsql .= " AND  (t1.company_name LIKE '".$argument['filter_search_str']."%' OR t1.id='".$argument['filter_search_str']."')";
        }

        if($argument['filter_created_from_date']!='' && $argument['filter_created_to_date']!='')
		{			
			
			$from = date_display_format_to_db_format($argument['filter_created_from_date']);  
			$to = date_display_format_to_db_format($argument['filter_created_to_date']);  
			$subsql.=" AND (t1.create_date BETWEEN '".$from."' AND '".$to."') ";
		}

		if($argument['assigned_user'])
        {
        	$subsql .= ' AND t1.assigned_user_id IN ('.$argument['assigned_user'].')';   
        }  

        if($argument['filter_by_company_available_for']!='')
		{		
			$is_export_filter='';
			$is_domestic_filter='';
			if($argument['filter_by_company_available_for']=='E')
			{
				$is_export_filter='Y';
			}
			else if($argument['filter_by_company_available_for']=='D')
			{				
				$is_domestic_filter='Y';
			}
			else
			{
				$lead_applicable_for_arr=explode(",",$argument['filter_by_company_available_for']);
				$is_export_filter='Y';
				$is_domestic_filter='Y';
			}
			
			$company=get_company_profile();
			$company_country_id=$company['country_id'];				
			if($is_export_filter=='Y' && $is_domestic_filter=='')
			{
				$subsql.=" AND t1.country_id !='".$company_country_id."'";
			}
			
			if($is_export_filter=='' && $is_domestic_filter=='Y')
			{
				$subsql.=" AND t1.country_id ='".$company_country_id."'";
			}
		}

		if($argument['filter_by_is_available_company_name'])
        {
        	if($argument['filter_by_is_available_company_name']=='Y')
        	{
        		$subsql .= ' AND (t1.company_name IS NOT NULL || t1.company_name!="")';
        	}
        	else if($argument['filter_by_is_available_company_name']=='N')
        	{
        		$subsql .= ' AND (t1.company_name IS NULL || t1.company_name="")';
        	}        	   
        }  

        if($argument['filter_by_is_available_email'])
        {
        	if($argument['filter_by_is_available_email']=='Y')
        	{
        		$subsql .= ' AND (t1.email IS NOT NULL || t1.email!="")';
        	}
        	else if($argument['filter_by_is_available_email']=='N')
        	{
        		$subsql .= ' AND (t1.email IS NULL || t1.email="")';
        	}        	   
        }  

        if($argument['filter_by_is_available_phone'])
        {
        	if($argument['filter_by_is_available_phone']=='Y')
        	{
        		$subsql .= ' AND (t1.mobile!="")';
        	}
        	else if($argument['filter_by_is_available_phone']=='N')
        	{
        		$subsql .= ' AND (t1.mobile IS NULL || t1.mobile="")';
        	}        	   
        } 

        if($argument['filter_last_contacted'])
        {
        	if($argument['filter_last_contacted']=='custom_date' && $argument['filter_last_contacted_custom_date']!='')
        	{
        		$days_ago = date_display_format_to_db_format($argument['filter_last_contacted_custom_date']);
        	}
        	else
        	{
        		$days_ago = date('Y-m-d', strtotime('-'.$argument['filter_last_contacted'].' days', strtotime(date('Y-m-d'))));
        	}    
        	$subsql .= " AND t1.last_mail_sent<='".$days_ago."'" ;    	   
        } 
        
        if($argument['filter_country'])
        {        	    
        	$subsql .= " AND t1.country_id IN ('".$argument['filter_country']."')";
        }

   
        $having="";
		if($argument['filter_company_type'])
        { 
			if(strtoupper($argument['filter_company_type'])=='PC')
			{
				$having .= " HAVING SUM(if(t2.current_stage_id=4,1,0)) >0";
			}
			else if(strtoupper($argument['filter_company_type'])=='FC')
			{				
				$having .= " HAVING SUM(if(t2.current_stage_id=4,1,0))=0";			
			}
			else if(strtoupper($argument['filter_company_type'])=='BC')
			{				
				$subsql.=" AND t1.is_blacklist='Y'";			
			}
			else
			{				
				
			}    
				
        }
        if($argument['source_ids']!='')
		{			
			$subsql.=" AND t1.source_id IN (".$argument['source_ids'].")";
		}	
		
		if($argument['business_type_id'])
        {        	    
        	$subsql .= " AND t1.business_type_id='".$argument['business_type_id']."'";
        }
        // SEARCH VALUE
        // ---------------------------------------

        $sql="SELECT 
        		t1.id,
        		t1.assigned_user_id,
        		t1.first_name,
        		t1.last_name,
        		t1.contact_person,
        		t1.designation,
        		t1.email,
        		t1.alt_email,
        		t1.mobile_country_code,
        		t1.mobile,
        		t1.alt_mobile_country_code,
        		t1.alt_mobile,
        		t1.landline_country_code,
        		t1.landline_std_code,
        		t1.landline_number,
        		t1.office_phone,
        		t1.website,
        		t1.company_name,
        		t1.address,
        		t1.city,
        		t1.state,
        		t1.country_id,
        		t1.zip,
        		t1.gst_number,
        		t1.create_date,
        		t1.short_description,
        		t1.source_id,
        		t1.modify_date,
        		t1.status,     
        		t1.last_mail_sent, 
				t1.is_blacklist,  
				t1.reference_name,        		
        		COUNT(t2.id) AS lead_count,
        		t3.name AS assigned_user_name,
        		t4.name AS city_name,
        		t5.name AS country_name,
        		SUM(if(t2.current_stage_id=4,1,0)) AS lead_won_count,
        		st.name AS state_name,
				count(distinct t6.id) AS contact_persion_count
			FROM customer AS t1 
			LEFT JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.status='1'
			LEFT JOIN user AS t3 ON t1.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN states AS st ON st.id=t1.state
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id	
			LEFT JOIN customer_contact_person AS t6 ON t1.id=t6.customer_id		
			WHERE t1.status='1' $subsql 
			GROUP BY t1.id $having
			$order_by_str 
			LIMIT $start,$limit ";		
			
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

    public function get_email_by_id($ids)
    {
    	$sql="SELECT id,contact_person,email from customer WHERE id IN ($ids)";
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

    function insertBulkMailHistort($data,$client_info=array())
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
		$this->client_db->insert('customer_bulk_mail_history',$data);
	}

	public function get_country_list($ids)
	{
		$sql="SELECT t1.id,t1.sortname AS code,
		t1.name FROM countries AS t1 
		INNER JOIN customer AS t2 ON t1.id=t2.country_id 
		WHERE t2.assigned_user_id IN ($ids) GROUP BY t2.country_id ORDER BY t1.name";
    	$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();die();
        //return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function add_bulk_mail_to_be_send($data)
	{
		if($this->client_db->insert('customer_bulk_mail_tmp',$data))
        {
           return $this->client_db->insert_id();
        }
        else
        {
          return false;
        }
	}

	public function get_bulk_mail_list($bulk_mail_id,$client_info=array())
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
		$subsql="";
		if($bulk_mail_id!='')
		{
			$subsql .=" AND bulk_mail_id='".$bulk_mail_id."'";
		}
		$sql="SELECT id,
		bulk_mail_id,
		updated_by_user_id,
		customer_id,
		from_email,
		from_name,
		to_mail,
		to_name,
		subject,
		body,
		attach_filename_with_path 
		FROM customer_bulk_mail_tmp WHERE 1=1 $subsql ORDER BY bulk_mail_id";
    	$query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();die();
        //return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function delete_bulk_mail_list($bulk_mail_id)
	{
		$this->client_db -> where('bulk_mail_id', $bulk_mail_id);
    	$this->client_db -> delete('customer_bulk_mail_tmp');
	}

	public function delete_bulk_mail_tmp($id,$client_info=array())
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

		$this->client_db -> where('id', $id);
    	$this->client_db -> delete('customer_bulk_mail_tmp');
	}

	function get_decision_for_indiamart($arg=array(),$client_info=array())
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
									// return array('msg'=>'no_customer_exist','customer_id'=>'','status'=>TRUE);
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
				return array('msg'=>'sender_uid missing','customer_id'=>'','status'=>FALSE);
			}

			
		}
		else
		{
			return array('msg'=>'sender_uid missing','customer_id'=>'','status'=>FALSE);
		}					
	}

	function get_decision_for_aajjo($arg=array(),$client_info=array())
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
					return array('msg'=>'email and mobile both missing','customer_id'=>'','status'=>FALSE);
				}	
															
			}		
		}
		else
		{
			return array('msg'=>'email and mobile both missing','customer_id'=>'','status'=>FALSE);
		}					
	}

	public function get_customers_by_emails($emails_arr)
	{
		$email_str ='';
		$return_arr=array();
		if(count($emails_arr))
		{
			foreach($emails_arr AS $email)
			{
				$email_str .="'".$email."',";
			}
			$email_str=rtrim($email_str,',');

			$sql="
				SELECT 
        		t1.id,
        		t1.assigned_user_id,
        		t1.first_name,
        		t1.last_name,
        		t1.contact_person,
        		t1.designation,
        		t1.email,
        		t1.alt_email,
        		t1.mobile_country_code,
        		t1.mobile,
        		t1.alt_mobile_country_code,
        		t1.alt_mobile,
        		t1.landline_country_code,
        		t1.landline_std_code,
        		t1.landline_number,
        		t1.office_phone,
        		t1.website,
        		t1.company_name,
        		t1.address,
        		t1.city,
        		t1.state,
        		t1.country_id,
        		t1.zip,
        		t1.gst_number,
        		t1.create_date,
        		t1.short_description,
        		t1.source_id,
        		t1.modify_date,
        		t1.status,     
        		t1.last_mail_sent,
        		t2.*, 
        		t3.*
			FROM customer AS t1 
			LEFT JOIN 
			(
				SELECT 
					id AS lead_id,
					customer_id,
					GROUP_CONCAT(id) AS lead_ids,
					COUNT(id) AS lead_count,
					SUM(if(current_stage_id=4,1,0)) AS won_count,
					SUM(if(current_stage_id=1,1,0)) AS pending_count,
        			SUM(if(current_stage_id=2,1,0)) AS quoted_count  
        		FROM lead WHERE status='1' 
        		GROUP BY customer_id
			)			 
			AS t2 ON t1.id=t2.customer_id  
			LEFT JOIN 
			(
				SELECT COUNT(lead_opportunity.lead_id) AS proposal_count,
				lead.customer_id AS cust_id,
				GROUP_CONCAT(DISTINCT lead.id) AS quoted_lead_ids
        		FROM lead 
        		inner JOIN lead_opportunity ON lead_opportunity.lead_id=lead.id GROUP BY lead.customer_id
			) AS t3 ON t1.id=t3.cust_id   
			WHERE t1.email IN (".$email_str.") AND t1.status='1' GROUP BY t1.id";
			$query = $this->client_db->query($sql,false);        
	        // echo $last_query = $this->client_db->last_query();//die();
	        
	        if($query->num_rows() > 0)
			{
				foreach ($query->result() as $row) 
				{ 
					$open_count=($row->pending_count+$row->quoted_count);
					$closed_count=($row->lead_count-($open_count+$row->won_count));
					$return_arr[]=array(
									'customer_id'=>$row->id,
									'email'=>$row->email,
									'contact_person'=>$row->contact_person,
									'company_name'=>$row->company_name,
									'lead_ids'=>$row->lead_ids,
									'lead_count'=>$row->lead_count,
									'open_count'=>$open_count,
									'proposal_count'=>$row->proposal_count,
									'closed_count'=>$closed_count,
									'quoted_lead_ids'=>$row->quoted_lead_ids,
									'lead_id'=>$row->lead_id,
									);
				}
			}
			
		}
		return $return_arr;
	}

	function get_decision_jd($arg=array(),$client_info=array())
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
								return array('msg'=>'email and mobile both missing','customer_id'=>'','status'=>FALSE);
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
					return array('msg'=>'email and mobile both missing','customer_id'=>'','status'=>FALSE);
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
					return array('msg'=>'email and mobile both missing','customer_id'=>'','status'=>FALSE);
				}	
															
			}		
		}
		else
		{
			return array('msg'=>'email and mobile both missing','customer_id'=>'','status'=>FALSE);
		}
		
						
	}

	function get_customer_detail_by_mobile($mobile,$client_info=array())
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
			ct.name AS city_name,
			st.name AS state_name,
			cnt.name AS country_name,
			source.name AS source_name
			FROM customer AS c		
			LEFT JOIN cities AS ct ON ct.id=c.city
			LEFT JOIN states AS st ON st.id=c.state
			LEFT JOIN countries AS cnt ON cnt.id=c.country_id
			LEFT JOIN source ON source.id=c.source_id 
			WHERE c.mobile='".$mobile."' AND c.status='1' LIMIT 1";
		$result=$this->client_db->query($sql);
		//return $result->row_array();

		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	function get_business_type()
	{
		$sql="SELECT id,name
			FROM tbl_customer_business_type 
			WHERE is_deleted='N'";
		$result=$this->client_db->query($sql);
		//return $result->result_array();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}


	function sms_customer_row($id,$client_info=array())
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
		$sql="SELECT 
			c.contact_person AS b_contact_person,
			c.company_name AS b_company_name,
			c.email AS b_email,
			c.mobile AS b_mobile,
			c.address AS b_address,
			c.zip AS b_zip,
			c.gst_number AS b_gst,
			c.website AS b_website,
			cnt.name AS b_country,
			st.name AS b_state,
			ct.name AS b_city,
			source.name AS b_source 
			FROM customer AS c		
			LEFT JOIN cities AS ct ON ct.id=c.city
			LEFT JOIN states AS st ON st.id=c.state
			LEFT JOIN countries AS cnt ON cnt.id=c.country_id
			LEFT JOIN source ON source.id=c.source_id 
			WHERE c.id='".$id."'";
		$result=$this->client_db->query($sql);
		//return $result->row_array();
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	public function GetCustomerRows($client_info=array())
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
		$sql="SELECT 
			t1.id,
			t1.assigned_user_id,
			t1.contact_person,
			if(t1.email!='',t1.email,'') AS email,
			if(t1.mobile!='',t1.mobile,'') AS mobile,
			if(t1.company_name!='',t1.company_name,'') AS company_name,
			t1.address,
			t1.source_id
			FROM customer AS t1 		
			WHERE t1.status='1' AND t1.is_blacklist='N' ORDER BY t1.id";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function GetCustomerWiseContactPerson($arg,$client_info=array())
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
		if($arg['customer_id'])
		{
			$subsql .=" AND t1.customer_id='".$arg['customer_id']."'";
		}	
		$sql="SELECT t1.id,t1.name,t1.email
			FROM customer_contact_person AS t1 		
			WHERE 1=1 $subsql ORDER BY t1.name";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	function customer_wise_contact_person_add($data,$client_info=array())
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

		if($this->client_db->insert('customer_contact_person',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}

	function customer_wise_contact_person_edit($data,$id,$client_info=array())
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
		if($this->client_db->update('customer_contact_person',$data))
		{			
			return true;
		}
		else
		{
			return false;
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
		//return $result->result_array();

		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	function customer_wise_contact_person_row($id='')
	{
		
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
		FROM customer_contact_person AS t1 WHERE t1.id='".$id."'";
		$result=$this->client_db->query($sql);
		//return $result->row_array();

		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	public function delete_customer_contact_person($id)
	{
		$this->client_db->where('id', $id);
    	$this->client_db->delete('customer_contact_person');
	}

	public function GetCustomerDataDislike($id,$client_info=array())
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
		
		$this->client_db->select('customer.email,customer.contact_person,customer.company_name,cities.name AS city_name,countries.name AS country_name');
		$this->client_db->from('customer');
		$this->client_db->join('countries', 'countries.id = customer.country_id', 'left');
		$this->client_db->join('cities', 'cities.id = customer.city', 'left');
		$this->client_db->where('customer.id',$id);
		$this->client_db->where('customer.status','1');
		$result=$this->client_db->get();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	public function GetQuotationWiseCustomerData($id='',$client_info=array())
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
		
		if($id){
			$sql="SELECT c.id,
				c.first_name,
				c.last_name,
				c.contact_person,
				c.designation,
				c.email,
				c.alt_email,
				c.mobile,
				c.alt_mobile,
				c.office_phone,
				c.website,
				c.company_name,
				c.address,
				c.zip,
				c.gst_number,			
				cnt.name AS b_country,
				st.name AS b_state,
				ct.name AS b_city 
				FROM customer AS c		
				LEFT JOIN cities AS ct ON ct.id=c.city
				LEFT JOIN states AS st ON st.id=c.state
				LEFT JOIN countries AS cnt ON cnt.id=c.country_id
				WHERE c.id='".$id."'";
			$query=$this->client_db->query($sql);
			if($query){
				$rows=$query->result();
				foreach($rows AS $row){					
					return array(
								'id'=>$row->id,
								'first_name'=>$row->first_name,
								'last_name'=>$row->last_name,
								'contact_person'=>$row->contact_person,
								'designation'=>$row->designation,
								'email'=>$row->email,
								'alt_email'=>$row->alt_email,
								'mobile'=>$row->mobile,
								'alt_mobile'=>$row->alt_mobile,
								'office_phone'=>$row->office_phone,
								'website'=>$row->website,
								'company_name'=>$row->company_name,
								'address'=>$row->address,
								'city'=>$row->b_city,
								'state'=>$row->b_state,
								'country'=>$row->b_country,
								'zip'=>$row->zip,
								'gst_number'=>$row->gst_number
							);
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

	// =================================================================================================
	// =================================================================================================
	// API	
    function get_list_count_api($argument=array(),$client_info=array())
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
        // ---------------------------------------
        // SEARCH VALUE
        if(isset($argument['search_str']))
		{			
			if($argument['search_str']){					
				$subsql .=" AND (t1.company_name LIKE '%".$argument['search_str']."%' || t1.contact_person LIKE '%".$argument['search_str']."%')";
			}			
		}
		if($argument['user_id_str'])
        {
        	$subsql .= ' AND t1.assigned_user_id IN ('.$argument['user_id_str'].')';   
        }  
        // SEARCH VALUE
        // ---------------------------------------
            
		$sql="SELECT 
			t1.id,
			t1.status
			FROM customer AS t1 
			LEFT JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.status='1'
			LEFT JOIN user AS t3 ON t1.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN states AS st ON st.id=t1.state
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id			
			WHERE t1.status='1' $subsql GROUP BY t1.id"; 
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
    
    function get_list_api($argument=array(),$client_info=array())
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
		$subsql='';	
		$limit=$argument['limit'];
      	$start=$argument['start'];
        // ---------------------------------------
        // SEARCH VALUE
        if(isset($argument['search_str']))
		{			
			if($argument['search_str']){					
				$subsql .=" AND (t1.company_name LIKE '%".$argument['search_str']."%' || t1.contact_person LIKE '%".$argument['search_str']."%')";
			}			
		}
		if($argument['user_id_str'])
        {
        	$subsql .= ' AND t1.assigned_user_id IN ('.$argument['user_id_str'].')';   
        }  
        // SEARCH VALUE
        // ---------------------------------------

        $sql="SELECT 
        		t1.id,
        		t1.assigned_user_id,        		
        		t1.contact_person,
        		t1.designation,
        		t1.email,
        		t1.alt_email,
        		t1.mobile_country_code,
        		t1.mobile,
        		t1.alt_mobile_country_code,
        		t1.alt_mobile,
        		t1.landline_country_code,
        		t1.landline_std_code,
        		t1.landline_number,
        		t1.office_phone,
        		t1.website,
        		t1.company_name,
        		t1.address,
        		t1.city,
        		t1.state,
        		t1.country_id,
        		t1.zip,
        		t1.gst_number,
        		t1.create_date,
        		t1.short_description,
        		t1.source_id,
        		t1.modify_date,
        		t1.status,     
        		t1.last_mail_sent, 
				t1.is_blacklist,  
				t1.reference_name,        		
        		COUNT(t2.id) AS lead_count,
        		t3.name AS assigned_user_name,
        		t4.name AS city_name,
        		t5.name AS country_name,
        		SUM(if(t2.current_stage_id=4,1,0)) AS lead_won_count,
        		st.name AS state_name,
				count(distinct t6.id) AS contact_persion_count
			FROM customer AS t1 
			LEFT JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.status='1'
			LEFT JOIN user AS t3 ON t1.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN states AS st ON st.id=t1.state
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id	
			LEFT JOIN customer_contact_person AS t6 ON t1.id=t6.customer_id		
			WHERE t1.status='1' $subsql 
			GROUP BY t1.id ORDER BY  t1.id DESC LIMIT $start,$limit ";		
		
        $query = $this->client_db->query($sql,false); 
		// echo $last_query = $this->client_db->last_query();die();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
    }

	function get_detail_api($argument=array(),$client_info=array())
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
		$subsql='';			
        // ---------------------------------------
        // SEARCH VALUE     
		if($argument['c_id'])
        {
        	$subsql .= ' AND t1.id ='.$argument['c_id'].'';   
        }   
		// if($argument['user_id_str'])
        // {
        // 	$subsql .= ' AND t1.assigned_user_id IN ('.$argument['user_id_str'].')';   
        // }  
        // SEARCH VALUE
        // ---------------------------------------

        $sql="SELECT 
        		t1.id,
        		t1.assigned_user_id,        		
        		t1.contact_person,
        		t1.designation,
        		t1.email,
        		t1.alt_email,
        		t1.mobile_country_code,
        		t1.mobile,
        		t1.alt_mobile_country_code,
        		t1.alt_mobile,
        		t1.landline_country_code,
        		t1.landline_std_code,
        		t1.landline_number,
        		t1.office_phone,
        		t1.website,
        		t1.company_name,
        		t1.address,
        		t1.city,
        		t1.state,
        		t1.country_id,
        		t1.zip,
        		t1.gst_number,
        		t1.create_date,
        		t1.short_description,
        		t1.source_id,
        		t1.modify_date,
        		t1.status,     
        		t1.last_mail_sent, 
				t1.is_blacklist,  
				t1.reference_name,        		
        		COUNT(t2.id) AS lead_count,
        		t3.name AS assigned_user_name,
        		t4.name AS city_name,
        		t5.name AS country_name,
        		SUM(if(t2.current_stage_id=4,1,0)) AS lead_won_count,
        		st.name AS state_name,
				count(distinct t6.id) AS contact_persion_count
			FROM customer AS t1 
			LEFT JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.status='1'
			LEFT JOIN user AS t3 ON t1.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN states AS st ON st.id=t1.state
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id	
			LEFT JOIN customer_contact_person AS t6 ON t1.id=t6.customer_id		
			WHERE t1.status='1' $subsql";		
		
        $query = $this->client_db->query($sql,false); 
		// echo $last_query = $this->client_db->last_query();die();
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
    }
	// API
	// =================================================================================================
	// =================================================================================================

	public function get_customers_wise_lead_count($customer_id,$client_info=array())
	{
		if(isset($client_info->db_name)){
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

		$return_arr=array();
		if(trim($customer_id)!='')
		{
			$sql="
			SELECT
			COUNT(T1.id) AS total_lead,
			SUM(if(T1.current_stage_id=4,1,0)) AS order_count,
			SUM(if(T1.current_stage_id=1,1,0)) AS active_lead,
			T7.is_paying_customer
			FROM lead AS T1

			LEFT JOIN (SELECT lead.customer_id AS custid,IF(  FIND_IN_SET ('4', GROUP_CONCAT( DISTINCT s_log.stage_id SEPARATOR ',' )), 'Y','N') AS is_paying_customer FROM lead LEFT JOIN lead_stage_log AS s_log ON s_log.lead_id=lead.id GROUP BY lead.customer_id) AS T7 ON T7.custid=T1.customer_id

			WHERE T1.status='1' AND T1.customer_id='".$customer_id."'
			GROUP BY T1.customer_id";
			$query = $this->client_db->query($sql,false);        
	        // echo $last_query = $this->client_db->last_query();//die();
	        
	        if($query->num_rows() > 0)
			{
				return $query->result();
			}
			
		}
		return $return_arr;
	}
}
?>