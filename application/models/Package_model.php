<?php
class Package_model extends CI_Model
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

    
	public function CreatePackage($data)
    {
		$this->client_db->insert('tbl_package', $data);
	}
	public function CreatePackageUser($data)
    {
		$this->client_db->insert('tbl_package_order', $data);
		return $this->client_db->insert_id();
	}
	public function UpdatePackageUser()
    {
    	$data=array('purchased_datetime'=>'0000-00-00 00:00:00','expire_date'=>'0000-00-00 00:00:00');		

		if($this->client_db->update('tbl_package_order',$data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}
	
	public function CreatePackageAttribute($data)
    {
		
		$this->client_db->insert('tbl_package_order_detail', $data);
		
	}
   

	public function GetPackageList()
	{

		$db2['hostname'] = "nilsoft123.powwebmysql.com";
		$db2['username'] = "developer";
		$db2['password'] = "unlock#1234";
		$db2['database'] = "lms_baba";
		$db2['dbdriver'] = "mysql";
		$db2['dbprefix'] = "";
		$db2['pconnect'] = FALSE;
		$db2['db_debug'] = FALSE;
		$db2['cache_on'] = FALSE;
		$db2['cachedir'] = "";
		$db2['char_set'] = "utf8";
		$db2['dbcollat'] = "utf8_general_ci";
		$db2['swap_pre'] = "";
		$db2['autoinit'] = TRUE;
		$db2['stricton'] = FALSE;

		$otherdb = $this->load->database($db2, TRUE);
		
		$otherdb->from('tbl_package');	
		//$otherdb->where('status','0');		

		$result=$otherdb->get();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		

	}
	
	public function GetPackageData($id)
	{
		$db2['hostname'] = "nilsoft123.powwebmysql.com";
		$db2['username'] = "developer";
		$db2['password'] = "unlock#1234";
		$db2['database'] = "lms_baba";
		$db2['dbdriver'] = "mysql";
		$db2['dbprefix'] = "";
		$db2['pconnect'] = FALSE;
		$db2['db_debug'] = FALSE;
		$db2['cache_on'] = FALSE;
		$db2['cachedir'] = "";
		$db2['char_set'] = "utf8";
		$db2['dbcollat'] = "utf8_general_ci";
		$db2['swap_pre'] = "";
		$db2['autoinit'] = TRUE;
		$db2['stricton'] = FALSE;

		$otherdb = $this->load->database($db2, TRUE);
		$otherdb->from('tbl_package');	
		$otherdb->where('id',$id);
		$result=$otherdb->get();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
		
	}
	public function GetCurrentPackageData()
	{
		$query ="SELECT * FROM tbl_package_order ORDER BY id DESC LIMIT 0,1";
     	$result = $this->client_db->query($query);		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
		
	}
	
	public function GetAttributeListByPackage($package_id)
	{
		$db2['hostname'] = "nilsoft123.powwebmysql.com";
		$db2['username'] = "developer";
		$db2['password'] = "unlock#1234";
		$db2['database'] = "lms_baba";
		$db2['dbdriver'] = "mysql";
		$db2['dbprefix'] = "";
		$db2['pconnect'] = FALSE;
		$db2['db_debug'] = FALSE;
		$db2['cache_on'] = FALSE;
		$db2['cachedir'] = "";
		$db2['char_set'] = "utf8";
		$db2['dbcollat'] = "utf8_general_ci";
		$db2['swap_pre'] = "";
		$db2['autoinit'] = TRUE;
		$db2['stricton'] = FALSE;

		$otherdb = $this->load->database($db2, TRUE);
		$otherdb->select('tbl_package_attribute_value.*,tbl_package_attribute.attribute_name as attribute_name,tbl_package_attribute.reserved_keyword as reserved_keyword');
		$otherdb->from('tbl_package_attribute_value');	
		$otherdb->join('tbl_package_attribute', 'tbl_package_attribute.id = tbl_package_attribute_value.package_attribute_id','left');
		$otherdb->where('tbl_package_attribute_value.package_id',$package_id);
		
		$result=$otherdb->get();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}
	
	public function GetPackageAttributeList()
	{

		$db2['hostname'] = "nilsoft123.powwebmysql.com";
		$db2['username'] = "developer";
		$db2['password'] = "unlock#1234";
		$db2['database'] = "lms_baba";
		$db2['dbdriver'] = "mysql";
		$db2['dbprefix'] = "";
		$db2['pconnect'] = FALSE;
		$db2['db_debug'] = FALSE;
		$db2['cache_on'] = FALSE;
		$db2['cachedir'] = "";
		$db2['char_set'] = "utf8";
		$db2['dbcollat'] = "utf8_general_ci";
		$db2['swap_pre'] = "";
		$db2['autoinit'] = TRUE;
		$db2['stricton'] = FALSE;

		$otherdb = $this->load->database($db2, TRUE);
		$otherdb->from('tbl_package_attribute_value');	
		//$otherdb->where('status','0');		

		$result=$otherdb->get();

		
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}
	
	public function GetPackageAttributeNameList()
	{

		$db2['hostname'] = "nilsoft123.powwebmysql.com";
		$db2['username'] = "developer";
		$db2['password'] = "unlock#1234";
		$db2['database'] = "lms_baba";
		$db2['dbdriver'] = "mysql";
		$db2['dbprefix'] = "";
		$db2['pconnect'] = FALSE;
		$db2['db_debug'] = FALSE;
		$db2['cache_on'] = FALSE;
		$db2['cachedir'] = "";
		$db2['char_set'] = "utf8";
		$db2['dbcollat'] = "utf8_general_ci";
		$db2['swap_pre'] = "";
		$db2['autoinit'] = TRUE;
		$db2['stricton'] = FALSE;

		$otherdb = $this->load->database($db2, TRUE);
		
		$otherdb->from('tbl_package_attribute');	
		//$otherdb>where('status','0');		

		$result=$otherdb->get();

		
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}

}

?>