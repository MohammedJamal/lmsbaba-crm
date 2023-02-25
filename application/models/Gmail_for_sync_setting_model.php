<?php
class Gmail_for_sync_setting_model extends CI_Model
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

	function AddData($data)
	{
		if($this->client_db->insert('user_wise_gmail_for_sync',$data))
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
		if($this->client_db->update('user_wise_gmail_for_sync',$data))
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
		$this->client_db->where('id', $id);
		$this->client_db->delete('user_wise_gmail_for_sync');
		return true;
	}

	public function GetAllList()
	{
		$sql="SELECT t1.id,
				t1.user_id,
				t1.gmail_address,t2.name AS user_name 
				FROM user_wise_gmail_for_sync AS t1
				INNER JOIN user AS t2 ON t1.user_id=t2.id";
		$query = $this->client_db->query($sql,false);        
		// echo $last_query = $this->client_db->last_query();die();
		$return=array();
		return $query->result_array();
	}

	public function GetDetails($id)
	{
		$sql="SELECT t1.id,
				t1.user_id,
				t1.gmail_address 
				FROM user_wise_gmail_for_sync AS t1 WHERE t1.id='".$id."'";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		return $query->row_array();
	}

	public function Chk_exist($user_id,$exist_user_id='')
	{
		$subsql="";
		if($exist_user_id!='')
		{
			$subsql .=" AND t1.user_id!='".$exist_user_id."'";
		}
		$sql="SELECT t1.id FROM user_wise_gmail_for_sync AS t1 WHERE t1.user_id='".$user_id."' $subsql";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		if($query->num_rows()==0)
		{
			return 'N';
		}
		else
		{
			return 'Y';
		}
	}

}
?>