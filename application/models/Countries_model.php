<?php

class Countries_model extends CI_Model
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

    
	// public function GetCountriesList()
	// {
	// 	$this->client_db->from('countries');
	// 	$this->client_db->select('id,name');	
	// 	$result=$this->client_db->get();		
	// 	return $result->result();
	// }

	public function GetCountriesList($ids='',$client_info=array())
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
		if($ids)
		{
			$subsql .=" AND t1.id IN ($ids)";
		}
		$sql="SELECT 
			t1.id,
			t1.name 
			FROM countries AS t1  WHERE 1=1 $subsql
			ORDER BY t1.name";	
		$query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();die();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
	}

	public function GetCountriesList_maindb($ids='')
	{
		$subsql="";
		if($ids)
		{
			$subsql .=" AND t1.id IN ($ids)";
		}
		$sql="SELECT 
			t1.id,
			t1.name 
			FROM countries AS t1  WHERE 1=1 $subsql
			ORDER BY t1.name";	
		$query = $this->fsas_db->query($sql,false); 
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
	}


	public function GetOnlyCountriesList($search_str='')
	{
		$subsql="";
		if($search_str)
		{
			$subsql .=" AND t1.name LIKE '%".$search_str."%'";
		}
		$sql="SELECT 
			t1.id,
			t1.name 
			FROM countries AS t1  WHERE 1=1 $subsql
			ORDER BY t1.name LIMIT 0,10";	
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

	public function get_country_by_iso($iso,$client_info=array())
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
		$sql="SELECT * FROM countries WHERE sortname='".strtoupper($iso)."'";
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

	public function get_country_by_name($name,$client_info=array())
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
   

}

?>