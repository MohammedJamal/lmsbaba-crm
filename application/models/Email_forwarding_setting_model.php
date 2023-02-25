<?php
class Email_forwarding_setting_model extends CI_Model
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

	function AddData($data)
	{
		if($this->client_db->insert('email_forwarding_settings',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function Editdata($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('email_forwarding_settings',$data))
		{			
			//return true;			
		}
		else
		{
			//return false;
		}	
	}

	// public function TruncateIndiamartCredentials()
	// {
	// 	$this->client_db->truncate('tradeindia_setting');	
	// 	return true;	
	// }

	public function Delete($id)
	{
		$this->client_db -> where('id', $id);
		$this->client_db -> delete('email_forwarding_settings');
		return true;
	}

	public function GetAllList()
	{
		$sql="SELECT t1.id,
				t1.mail_name,
				t1.mail_keyword,
				t1.is_mail_send,
				t1.is_send_mail_to_client,
				t1.is_send_relationship_manager,
				t1.is_send_manager,
				t1.is_send_skip_manager				
				FROM email_forwarding_settings AS t1 ORDER BY t1.sort_order";
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		$return=array();
		//return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function GetDetails($id,$client_info=array())
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
		$sql="SELECT t1.id,
				t1.mail_name,
				t1.mail_keyword,
				t1.is_mail_send,
				t1.is_send_mail_to_client,
				t1.is_send_relationship_manager,
				t1.is_send_manager,
				t1.is_send_skip_manager				
				FROM email_forwarding_settings AS t1 WHERE id='".$id."'";
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
		
	}
}
?>