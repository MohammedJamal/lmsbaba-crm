<?php
class Indiamart_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() {
        parent::__construct();
		// $this->load->database();
		$this->user_arr=array();
		$this->class_name=$this->router->fetch_class();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    }

	function insert($data,$client_info=array())
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
		if($this->client_db->insert('indiamart_tmp',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}

	function truncate($client_info=array())
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
		if($this->client_db->truncate('indiamart_tmp'))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}

	public function get_rows($im_s_id='',$client_info=array())
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
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
		
	}

	public function update($data,$id,$client_info=array())
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
		if($this->client_db->update('indiamart_tmp',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}
}

?>