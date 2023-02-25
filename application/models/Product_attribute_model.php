<?php
class Product_attribute_model extends CI_Model
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


	public function GetAttributeListAll()
	{

		/*$sql="SELECT attribute_master.id as attribute_id,attribute_values.id as id, attribute_values.name as name, GROUP_CONCAT(attribute_values.value SEPARATOR ',') as value, GROUP_CONCAT(attribute_values.id SEPARATOR ',') as id_value FROM attribute_master LEFT JOIN attribute_values ON attribute_master.id=attribute_values.attribute_id GROUP BY attribute_values.attribute_id ";*/
		$sql="SELECT *,GROUP_CONCAT(attribute_values.value SEPARATOR ',') as all_value FROM attribute_values group by attribute_id";
		$result=$this->client_db->query($sql);
		return $result->result();

	}
	
	public function GetAttributeListDropdown()
	{

		/*$sql="SELECT attribute_master.id as attribute_id,attribute_values.id as id, attribute_values.name as name, GROUP_CONCAT(attribute_values.value SEPARATOR ',') as value, GROUP_CONCAT(attribute_values.id SEPARATOR ',') as id_value FROM attribute_master LEFT JOIN attribute_values ON attribute_master.id=attribute_values.attribute_id GROUP BY attribute_values.attribute_id ";*/
		$sql="SELECT * FROM attribute_master";
		$result=$this->client_db->query($sql);
		return $result->result();

	}
	
	public function GetAttributeList($id)
	{

		$sql="SELECT * FROM attribute_values where attribute_id='".$id."'";
		$result=$this->client_db->query($sql);
		return $result->result();

	}
	
	public function GetAttributeListByProduct($product_id)
	{

		$sql="SELECT attr_val.*,GROUP_CONCAT(attr_val.id SEPARATOR ',') as id_value FROM product_attribute left join attribute_values as attr_val on attr_val.id=product_attribute.attribute_value_id where product_attribute.product_id='".$product_id."' group by attr_val.attribute_id";
		$result=$this->client_db->query($sql);
		return $result->result();

	}
	
	public function GetAttributeListIDByProduct($product_id)
	{

		$sql="SELECT GROUP_CONCAT(attr_val.id SEPARATOR ',') as id_value,attr_val.attribute_id as id_attr FROM product_attribute inner join attribute_values as attr_val on attr_val.id=product_attribute.attribute_value_id where product_attribute.product_id='".$product_id."' group by attr_val.attribute_id";
		$result=$this->client_db->query($sql);
		return $result->result();

	}
	
	

	

	public function CreateAttribute($data)
	{

		if($this->client_db->insert('attribute_master',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function CreateProductAttribute($data)
	{

		if($this->client_db->insert('product_attribute',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function CreateAttributeValue($data)
	{
		if($this->client_db->insert('attribute_values',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}
	


	public function UpdateAttribute($data,$id)
	{
		$this->client_db->where('id',$id);

		if($this->client_db->update('attribute_master',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}
	
	public function UpdateAttributeValue($data,$id)
	{
		$this->client_db->where('id',$id);

		if($this->client_db->update('attribute_values',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}

	public function DeleteAttribute($id)
	{
		$this->client_db->where('id',$id);

		if($this->client_db->delete('attribute_master'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function DeleteAttributeValue($id)
	{
		$this->client_db->where('id',$id);

		if($this->client_db->delete('attribute_values'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function DeleteValueByAttribute($id)
	{
		$this->client_db->where('attribute_id',$id);

		if($this->client_db->delete('attribute_values'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function DeleteProductAttribute($product_id)
	{
		$this->client_db->where('product_id',$product_id);

		if($this->client_db->delete('product_attribute'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function GetAttributeData($id)
	{
		$sql="SELECT id, name, GROUP_CONCAT(`value` SEPARATOR ',') as value, GROUP_CONCAT(`id` SEPARATOR ',') as id_value FROM attribute_values where attribute_id='".$id."' GROUP BY `attribute_id`";
		$result=$this->client_db->query($sql);
		
		return $result->row();

	}

}

?>