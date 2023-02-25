<?php

class Menu_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	function __construct() {
        parent::__construct();
		// $this->load->database();
		$this->user_arr=array();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
		
    }
    
    public function GetMenuListAll()
	{
		$this->client_db->from('menu_permission');
		$result=$this->client_db->get();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}
	 public function GetMenuListByUser($user_id)
	{
		$this->client_db->from('user_wise_permission');
		$this->client_db->where('user_id',$user_id);
		$result=$this->client_db->get();
		
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
	
	public function GetAdminMenuList($user_id)
	{
		$sql="select mp.*,mp.id as menu_id,mp.menu as menu_name,uwp.create as created,uwp.update as updated,uwp.delete as deleted,uwp.view as view from `menu_permission` as mp left join `user_wise_permission` as uwp on uwp.menu_id=mp.id and uwp.user_id='".$user_id."'";
		$result = $this->client_db->query($sql);

		
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}
	
	public function DeleteAdminMenu($user_id)
	{
		if($this->client_db->delete('user_wise_permission', array('user_id' => $user_id)))
		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}
	function CreateMasterMenu($menu_data)
	{
		if($this->client_db->insert('user_wise_permission',$menu_data))
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