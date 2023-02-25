<?php
class Pre_define_comment_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	function __construct() 
	{
        parent::__construct();
		// $this->load->database();
		$this->user_arr=array();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    } 
	public function GetLeadUpdatePreDefineComments($user_id,$type='LU',$client_info=array())
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
		$subsql .=" AND type='".$type."'";
		$sql="SELECT id,
				type,
				user_id,
				title,
				comment 
				FROM tbl_pre_define_comments 
				WHERE user_id='".$user_id."' $subsql 
				ORDER BY id DESC";
		$result=$this->client_db->query($sql);
		//echo $sql;
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}
	 
	function add($data)
	{
		if($this->client_db->insert('tbl_pre_define_comments',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}	
	function edit($data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('tbl_pre_define_comments',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function delete($id)
	{
		$this->client_db->where('id', $id);
		if($this->client_db->delete('tbl_pre_define_comments'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>