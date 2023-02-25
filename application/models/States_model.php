<?php
class States_model extends CI_Model
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

    public function GetStatesList($country_id,$client_info=array())
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
		$this->client_db->from('states');		
		$this->client_db->where('country_id',$country_id);
		$this->client_db->select('id,name,country_id');		
		$result=$this->client_db->get();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function GetStatesList_maindb($country_id)
	{
		$this->fsas_db->from('states');		
		$this->fsas_db->where('country_id',$country_id);
		$this->fsas_db->select('id,name,country_id');		
		$result=$this->fsas_db->get();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function GetOnlyIndianStatesListByID($ids='')
	{
		$subsql="";
		if($search_str)
		{
			$subsql .=" AND t1.id IN ($ids)";
		}
		$sql="SELECT 
			t1.id,
			t1.name,
			t1.country_id 
			FROM states AS t1  WHERE 1=1 $subsql
			ORDER BY t1.name";	
		$query = $this->client_db->query($sql,false);        
        // $last_query = $this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
	}


	public function GetOnlyIndianStatesList($search_str='')
	{
		$subsql="";
		if($search_str)
		{
			$subsql .=" AND t1.name LIKE '%".$search_str."%'";
		}
		$sql="SELECT 
			t1.id,
			t1.name 
			FROM states AS t1  WHERE t1.country_id='101' $subsql
			ORDER BY t1.name LIMIT 0,10";	
		$query = $this->client_db->query($sql,false);        
        // $last_query = $this->client_db->last_query();
        
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function get_state_id_by_name($name,$client_info=array())
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
		$sql="SELECT id,name,country_id FROM states WHERE replace(LOWER(name),' ', '')='".str_replace(' ', '', strtolower(str_replace('\'', '',$name)))."'";
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

}

?>