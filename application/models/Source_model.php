<?php
class Source_model extends CI_Model
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

	public function GetSourceListAll($client_info=array())
	{
		// $this->client_db->from('source');
		// $result=$this->client_db->get();
		// return $result->result();

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

		$sql="SELECT id,parent_id,name,alias_name FROM source GROUP BY name ORDER BY name ASC";
		$query=$this->client_db->query($sql);	
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
			
	}
	public function GetSourceListAllOrderByDesc()
	{
		// $this->client_db->from('source');
		// $result=$this->client_db->get();
		// return $result->result();

		$sql="SELECT id,parent_id,name,alias_name FROM source GROUP BY name ORDER BY 1 DESC";
		$query=$this->client_db->query($sql);	
		//return $query->result();
		
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}
	function add($data,$client_info=array())
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
		if($this->client_db->insert('source',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function get_source_id_by_name($text,$client_info=array())
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
		$sql="SELECT id FROM source WHERE replace(LOWER(name) , ' ','')='".$text."' LIMIT 1";
		$query=$this->client_db->query($sql);	
		if($query){
			if($query->num_rows()>0)
			{
				$row=$query->row();
				return $row->id;
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

	public function get_source()
	{
		$sql="SELECT id,name FROM source ORDER BY name ";
		$query=$this->client_db->query($sql);		
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
		
	}
}

?>