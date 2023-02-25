<?php
class Sms_setting_model extends CI_Model
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

	function AddCredentials($data)
	{
		if($this->client_db->insert('sms_api',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function EditCredentials($data,$id,$client_info=array())
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
		if($this->client_db->update('sms_api',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	public function TruncateSmsCredentials()
	{
		$this->client_db->truncate('sms_api');	
		return true;	
	}

	public function DeleteCredentials($id)
	{
		$this->client_db->where('sms_api_id', $id);
		$this->client_db->delete('sms_template');

		$this->client_db->where('id', $id);
		$this->client_db->delete('sms_api');		
		return true;
	}

	public function GetCredentials($client_info=array())
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
		$sql="SELECT t1.id,
				t1.sms_service_provider_id,
				t1.sender,
				t1.apikey,
				t1.entity_id,
				t1.name,
				t2.name AS service_provider 
				FROM sms_api AS t1 INNER JOIN sms_service_provider AS t2 ON t1.sms_service_provider_id=t2.id ";
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
		
	}

	public function GetCredentialsDetails($id)
	{
		$sql="SELECT id,
				sms_service_provider_id,
				sender,
				apikey,
				entity_id,
				name
				FROM sms_api WHERE id='".$id."'";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
		
		
	}

	public function EditTemplate($data,$id,$client_info=array())
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
		if($this->client_db->update('sms_template',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	function AddTemplate($data)
	{
		if($this->client_db->insert('sms_template',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function GetTemplateList($sms_api_id)
	{
		$sql="SELECT 
		t1.id,
		t1.sms_api_id,
		t1.template_id,
		t1.name,
		t1.text,
		t2.name AS sms_api_name 
		FROM sms_template AS t1 INNER JOIN sms_api AS t2 ON t1.sms_api_id=t2.id		
		WHERE t1.sms_api_id='".$sms_api_id."' ORDER BY t1.name";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}
			
	}

	public function GetTemplateDetails($id,$client_info=array())
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
		
		$sql="SELECT t1.id,
					t1.sms_api_id,
					t1.template_id,
					t1.name,
					t1.text,
					t2.sms_service_provider_id AS api_sms_service_provider_id,
					t2.sender AS api_sender,
					t2.apikey AS api_apikey,
					t2.entity_id AS api_entity_id,
					t2.name AS api_name
				FROM sms_template AS t1 
				INNER JOIN sms_api AS t2 ON t1.sms_api_id=t2.id WHERE t1.id='".$id."'";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		if($query){
			return $query->row_array();	
		}
		else{
			return array();
		}
			
	}

	public function DeleteTemplate($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('sms_template');		
		return true;
	}

}
?>