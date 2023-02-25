<?php
class Group_wise_category_model extends CI_Model
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

	public function add($data)
	{

		if($this->client_db->insert('group_wise_category',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function edit($data,$id)
	{
		$this->client_db->where('id',$id);

		if($this->client_db->update('group_wise_category',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}

	public function get_list($parent_id)
	{
		$subsql="";
		$sql="SELECT id,parent_id,name,is_active FROM group_wise_category 
		WHERE is_active='Y' AND parent_id='".$parent_id."' ORDER BY name ASC";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}
}

?>