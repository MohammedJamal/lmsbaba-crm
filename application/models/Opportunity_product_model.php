<?php
class Opportunity_product_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	function __construct() {
        parent::__construct();
		$this->load->database();
		$this->user_arr=array();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    }

    
	public function CreateOportunityProduct($data,$client_info=array())
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

		if($this->client_db->insert('opportunity_product',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function update($data_post,$id,$client_info=array())
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

		$this->client_db->where('id',$id);
		if($this->client_db->update('opportunity_product',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function delete($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('opportunity_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function UpdateOportunityProduct($quantity,$price,$discount,$product_id,$currency_id,$opp_id)
	{
		$this->client_db->where('product_id',$product_id);
		$this->client_db->where('opportunity_id',$opp_id);
		$data=array('unit'=>$quantity,'price'=>$price,'discount'=>$discount,'currency_type'=>$currency_id);

		if($this->client_db->update('opportunity_product',$data))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	
	public function GetOpportunityProductList($opportunity_id,$client_info=array())
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
		
		$sql="SELECT opportunity_product.id,
		opportunity_product.opportunity_id,
		opportunity_product.product_id,
		opportunity_product.unit,
		opportunity_product.unit_type,
		opportunity_product.price,
		opportunity_product.currency_type,
		opportunity_product.discount,
		opportunity_product.gst,
		opportunity_product.create_date,
		opportunity_product.status,
		currency.name as currency_type_name,
		currency.code as currency_type_code,
		currency.name as currency_type_name,
		unit_type.type_name as unit_type_name,
		product_varient.name as name 
		FROM opportunity_product 
		LEFT JOIN currency on opportunity_product.currency_type=currency.id 
		LEFT JOIN unit_type on opportunity_product.unit_type=unit_type.id 
		LEFT JOIN product_varient on opportunity_product.product_id=product_varient.id 
		WHERE opportunity_product.opportunity_id='".$opportunity_id."' 
		ORDER BY opportunity_product.create_date DESC";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function GetOpportunityProduct($opportunity_id,$pid)
	{
		
		$sql="SELECT opportunity_product.*,currency.name as currency_type_name,
		currency.code as currency_type_code,
		currency.name as currency_type_name,
		unit_type.type_name as unit_type_name,
		product_varient.name as name 
		FROM opportunity_product 
		LEFT JOIN currency on opportunity_product.currency_type=currency.id 
		LEFT JOIN unit_type on opportunity_product.unit_type=unit_type.id 
		LEFT JOIN product_varient on opportunity_product.product_id=product_varient.id 
		WHERE opportunity_product.opportunity_id='".$opportunity_id."' AND opportunity_product.product_id='".$pid."'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	public function DeleteOppProduct($product_id,$opportunity_id)
	{
		$this->client_db->where('product_id',$product_id);
		$this->client_db->where('opportunity_id',$opportunity_id);

		if($this->client_db->delete('opportunity_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	public function DeleteOppProductByOpportunity($opportunity_id)
	{
		
		$this->client_db->where('opportunity_id',$opportunity_id);

		if($this->client_db->delete('opportunity_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function GetTempAndOppProductList($user_id,$opportunity_id)
	{
		
		$sql="SELECT product_varient.name as name,op.id as id,op.product_id as product_id,op.unit as unit,op.unit_type as unit_type,op.price as price,op.currency_type as currency_type,op.discount as discount,currency.name as currency_type_name,currency.name as currency_type_name,unit_type.type_name as unit_type_name FROM `opportunity_product` AS op INNER JOIN product_varient on product_varient.id = op.product_id left join currency on op.currency_type=currency.id left join unit_type on op.unit_type=unit_type.id WHERE op.opportunity_id='".$opportunity_id."' 
		
		UNION ALL SELECT product_varient.name as name,tp.id as id,tp.product_id as product_id,tp.unit as unit,tp.unit_type as unit_type,tp.price as price,tp.currency_type as currency_type,tp.discount as discount,currency.name as currency_type_name,currency.name as currency_type_name,unit_type.type_name as unit_type_name FROM `temp_selected_product` AS tp INNER JOIN product_varient on product_varient.id = tp.product_id left join currency on tp.currency_type=currency.id left join unit_type on tp.unit_type=unit_type.id WHERE tp.user_id='".$user_id."' ";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
	
	public function ProdExistCheck($product_id,$opportunity_id)
	{
		$sql="SELECT * FROM opportunity_product where `product_id`='".$product_id."' and `opportunity_id`='".$opportunity_id."'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	public function GetOpportunityProducts($opportunity_id)
	{
		
		$sql="SELECT 
		id,
		opportunity_id,
		product_id,
		unit,
		unit_type,
		price,
		currency_type,
		discount,
		gst,
		create_date,
		status
		FROM opportunity_product 
		WHERE opportunity_id='".$opportunity_id."' 
		ORDER BY create_date DESC";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	function get_product_str($opp_id)
	{
		$sql="SELECT GROUP_CONCAT(CONCAT(t1.product_id,'#',t2.name,'#',t1.price)) AS p_str FROM opportunity_product AS t1 INNER JOIN product_varient AS t2 ON t1.product_id=t2.id WHERE t1.opportunity_id='".$opp_id."' GROUP BY t1.opportunity_id";
		// echo $sql; die();
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row()->p_str;
		}
		else{
			return '';
		}
	}
	
	function get_tagged_opportunity_product($opp_id)
    {
		$subsql="";		
		if($opp_id)
		{
			$subsql .=" AND opportunity_id='".$opp_id."'";
		}
    	$sql="SELECT product_id FROM opportunity_product 
		WHERE 1=1  $subsql";
		$query = $this->client_db->query($sql,false); 
		
		if($result){
			return $query->result_array();
		}
		else{
			return array();
		}
    }

	
}

?>