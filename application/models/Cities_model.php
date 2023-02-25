<?php

class Cities_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() {
        parent::__construct();
        $this->class_name=$this->router->fetch_class();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    }
    
	public function GetCitiesList($state_id,$client_info=array())
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
		$this->client_db->from('cities');		
		$this->client_db->where('state_id',$state_id);
		$this->client_db->select('id,name,state_id');	
		$result=$this->client_db->get();
		//  echo $last_query = $this->client_db->last_query();die();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}
	public function GetCitiesList_maindb($state_id)
	{
		$this->fsas_db->from('cities');		
		$this->fsas_db->where('state_id',$state_id);
		$this->fsas_db->select('id,name,state_id');	
		$result=$this->fsas_db->get();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}
	public function GetAllIndianCitiesList($ids='')
	{
		$subsql="";	
		if($ids)
		{
			$subsql .=" AND t1.id IN ($ids)";
		}	
		$sql="SELECT 
			t1.id,
			t1.name 
			FROM cities AS t1 
			INNER JOIN states as t2 ON t1.state_id=t2.id WHERE t2.country_id='101' $subsql
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

	public function GetOnlyIndianCitiesList($search_str='')
	{
		$subsql="";
		if($search_str)
		{
			$subsql .=" AND t1.name LIKE '%".$search_str."%'";
		}
		$sql="SELECT 
			t1.id,
			t1.name 
			FROM cities AS t1 
			INNER JOIN states as t2 ON t1.state_id=t2.id WHERE t2.country_id='101' $subsql
			ORDER BY t1.name LIMIT 0,10";	
		$query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();die();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
        
	}
	public function get_city_id_by_name($name,$client_info=array())
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
		else{
			return false;
		}
		
	}

	public function get_city_by_id($id,$client_info=array())
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
		$sql="SELECT id,name,state_id FROM cities WHERE id='".$id."'";
		$query=$this->client_db->query($sql);	
		if($query){
			if($query->num_rows()>0)
			{
				return $query->row();
			}
			else
			{
				return (object)array();
			}
		}
		else{
			return (object)array();
		}		
	}

	public function get_city_id_and_state_id_by_name($text,$client_info=array())
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
		$sql="SELECT id,state_id FROM cities WHERE replace(LOWER(name) , ' ','')='".$text."' LIMIT 1";
		$query=$this->client_db->query($sql);	
		if($query){
			if($query->num_rows()>0)
			{
				$row=$query->row();
				return array('city_id'=>$row->id,'state_id'=>$row->state_id);
			}
			else
			{
				return array('city_id'=>'','state_id'=>'');
			}
		}	
		else{
			return array('city_id'=>'','state_id'=>'');
		}
		
	}
}

?>