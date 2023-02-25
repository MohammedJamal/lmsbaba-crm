<?php
class Product_model extends CI_Model
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


	public function GetProductListAll()
	{	

		$sql="SELECT product_varient.*,
				product_images.file_name, 
				product_varient.id AS product_id, 
				product_varient.name AS name, 
				currency.name AS currency_type_name, 
				currency.code AS currency_type_code, 
				unit_type.type_name AS unit_type_name,
				product_pdf.file_name as brochure 
				FROM product_varient 
				LEFT JOIN product_images ON product_varient.id=product_images.varient_id 
				LEFT JOIN currency ON product_varient.currency_type=currency.id 
				LEFT JOIN unit_type on product_varient.unit_type=unit_type.id 
				LEFT JOIN product_pdf ON product_varient.id=product_pdf.varient_id  
				WHERE product_varient.parent_id='0' 
				and product_varient.status='0' ORDER BY product_varient.id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}

	public function GetProductDetail($id)
	{
		$sql="SELECT 
				product_varient.name as product_name,
				product_varient.id,
				product_varient.parent_id,
				product_varient.group_id,
				product_varient.cate_id,
				product_varient.name,
				product_varient.currency_type,
				product_varient.unit_type,
				product_varient.description,
				product_varient.long_description,
				product_varient.code,
				product_varient.attachment,
				product_varient.price,
				product_varient.unit,
				product_varient.minimum_order_quantity,
				product_varient.status,
				product_varient.disabled_reason,
				product_varient.disabled_reason_text,
				product_varient.gst_percentage,
				product_varient.hsn_code,
				product_varient.youtube_video,
				product_varient.product_available_for,
				product_varient.product_added_by,
				product_varient.product_last_modified_by,
				product_varient.date_modified,
				product_varient.date_added,
				product_varient.id AS product_id,				
				GROUP_CONCAT(
							DISTINCT CONCAT(product_images.id,'_',product_images.file_name)
							SEPARATOR '^'
				) AS image_files,
				product_pdf.id as pdf_file_id,
				product_pdf.file_name as pdf_file_name,
				currency.name as currency_type_name,
				unit_type.type_name as unit_type_name,
				currency.code AS currency_type_code,
				GROUP_CONCAT(
							DISTINCT CONCAT('@',vendor_tag.vendor_id,'_',vendor_tag.price,'_',vendor_tag.currency_type,'_',vendor_tag.unit,'_',vendor_tag.unit_type) 
							ORDER BY vendor_tag.id 
							SEPARATOR '^'
				) AS v_tag_str
				FROM product_varient 
				LEFT JOIN product_images ON product_varient.id=product_images.varient_id 
				LEFT JOIN product_pdf ON product_varient.id=product_pdf.varient_id 
				LEFT JOIN currency on product_varient.currency_type=currency.id 
				LEFT JOIN unit_type on product_varient.unit_type=unit_type.id 
				LEFT JOIN vendor_productvarient_tag AS vendor_tag on product_varient.id=vendor_tag.product_varient_id 
				WHERE product_varient.id='".$id."' GROUP BY product_varient.id";
		//echo $sql;die();
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	public function GetProductWiseImagesList($pid)
	{
		$sql="SELECT * FROM product_images WHERE varient_id='".$pid."' ORDER BY id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result_array();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetProductWisePdfList($pid)
	{
		$sql="SELECT * FROM product_pdf WHERE varient_id='".$pid."' ORDER BY id DESC";
		$result=$this->client_db->query($sql);
		//return $result->result_array();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	
	public function GetSKUListAll($parent_id)
	{
		$sql="SELECT product_varient.name as product_name,product_varient.*,product_images.file_name as file_name,product_pdf.file_name as brochure,currency.name as currency_type_name,unit_type.type_name as unit_type_name, currency.code AS currency_type_code FROM product_varient LEFT JOIN product_images ON product_varient.id=product_images.varient_id LEFT JOIN product_pdf ON product_varient.id=product_pdf.varient_id LEFT JOIN currency on product_varient.currency_type=currency.id left join unit_type on product_varient.unit_type=unit_type.id where (product_varient.parent_id='".$parent_id."' OR product_varient.id='".$parent_id."') and product_varient.status='0' GROUP BY product_varient.id ORDER BY product_varient.id ASC";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
	public function GetProductSKUList($parent_id)
	{
		$sql="SELECT product_varient.name as product_name,product_varient.*,product_images.file_name,currency.name as currency_type_name,unit_type.type_name as unit_type_name, currency.code AS currency_type_code FROM product_varient LEFT JOIN product_images ON product_varient.id=product_images.varient_id LEFT JOIN currency on product_varient.currency_type=currency.id left join unit_type on product_varient.unit_type=unit_type.id where (product_varient.parent_id='".$parent_id."' OR product_varient.id='".$parent_id."') and product_varient.status='0' ORDER BY product_varient.id ASC";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
	
	public function GetSKUData($id)
	{
		$sql="SELECT 
				product_varient.name as product_name,product_varient.*,
				product_images.id as image_file_name_id,
				product_images.file_name as file_name,
				product_pdf.file_name as brochure,
				currency.name as currency_type_name,
				unit_type.type_name as unit_type_name,
				currency.code AS currency_type_code 
				FROM product_varient 
				LEFT JOIN product_images ON product_varient.id=product_images.varient_id 
				LEFT JOIN product_pdf ON product_varient.id=product_pdf.varient_id 
				LEFT JOIN currency on product_varient.currency_type=currency.id 
				LEFT JOIN unit_type on product_varient.unit_type=unit_type.id 
				WHERE product_varient.id='".$id."' and product_varient.status='0'";
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	public function GetProductListById($id,$client_info=array())
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

		$sql="SELECT 
				product_varient.id,
				product_varient.parent_id,
				product_varient.group_id,
				product_varient.cate_id,
				product_varient.name,
				product_varient.currency_type,
				product_varient.unit_type,
				product_varient.description,
				product_varient.long_description,
				product_varient.code,
				product_varient.attachment,
				product_varient.price,
				product_varient.unit,
				product_varient.minimum_order_quantity,
				product_varient.status,
				product_varient.disabled_reason,
				product_varient.disabled_reason_text,
				product_varient.gst_percentage,
				product_varient.hsn_code,
				product_varient.youtube_video,
				product_varient.product_available_for,
				product_varient.product_added_by,
				product_varient.product_last_modified_by,
				product_varient.date_modified,
				product_varient.date_added,
				product_images.file_name, product_varient.id AS product_id, product_varient.name AS name, currency.name AS currency_type_name, unit_type.type_name AS unit_type_name, product_varient.unit_type AS unit_type_id, product_varient.currency_type AS currency_type, currency.code AS currency_type_code FROM product_varient LEFT JOIN product_images ON product_varient.id=product_images.varient_id LEFT JOIN currency ON product_varient.currency_type=currency.id LEFT JOIN unit_type on product_varient.unit_type=unit_type.id WHERE product_varient.id='".$id."' and product_varient.status='0' ORDER BY product_varient.id DESC";
		$result=$this->client_db->query($sql);
		//return $result->row();

		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
		
		

	}
	
	public function GetProductListFilter($search_data)
	{
		$subsql='';
		// ============================================
		if($search_data['temp_prod_id']!='')
		{
			$subsql .=" AND product_varient.id NOT IN (".$search_data['temp_prod_id'].")";
		}

		if($search_data['search_keyword']!='')
		{
			$subsql .=" AND (product_varient.name LIKE '%".$search_data['search_keyword']."%' || product_varient.code LIKE '%".$search_data['search_keyword']."%')";
		}

		if($search_data['search_group']!='')
        {
            $subsql .= " AND  product_varient.group_id = '".$search_data['search_group']."'";
        }
		if($search_data['search_category']!='')
        {
            $subsql .= " AND  product_varient.cate_id = '".$search_data['search_category']."'";
        }

		if(($search_data['po_pro_forma_invoice_id']!='' || $search_data['po_invoice_id']!='') && $search_data['is_pfi_or_inv']!='')
		{
			if($search_data['is_pfi_or_inv']=='pfi')
			{
				$subsql .=" AND product_varient.id NOT IN  (SELECT product_id FROM po_pro_forma_invoice_product WHERE po_pro_forma_invoice_id='".$search_data['po_pro_forma_invoice_id']."')";
			}
			else if($search_data['is_pfi_or_inv']=='inv')
			{
				$subsql .=" AND product_varient.id NOT IN  (SELECT product_id FROM po_invoice_product WHERE po_invoice_id='".$search_data['po_invoice_id']."')";
			}
			
		}

		// ============================================
		$sql="SELECT 
				product_varient.id,
				product_varient.parent_id,
				product_varient.group_id,
				product_varient.cate_id,
				product_varient.name,
				product_varient.currency_type,
				product_varient.unit_type,
				product_varient.description,
				product_varient.long_description,
				product_varient.code,
				product_varient.attachment,
				product_varient.price,
				product_varient.unit,
				product_varient.minimum_order_quantity,
				product_varient.status,
				product_varient.disabled_reason,
				product_varient.disabled_reason_text,
				product_varient.gst_percentage,
				product_varient.hsn_code,
				product_varient.youtube_video,
				product_varient.product_available_for,
				product_varient.product_added_by,
				product_varient.product_last_modified_by,
				product_varient.date_modified,
				product_varient.date_added,
				product_images.file_name, 
				product_varient.id AS product_id, 
				currency.name AS currency_type_name, 
				unit_type.type_name AS unit_type_name, 
				currency.code AS currency_type_code 
				FROM product_varient  
				LEFT JOIN product_images ON product_varient.id=product_images.varient_id 
				LEFT JOIN currency ON product_varient.currency_type=currency.id 
				LEFT JOIN unit_type on product_varient.unit_type=unit_type.id WHERE product_varient.status='0' AND product_varient.is_deleted='N' $subsql  GROUP BY product_varient.id ORDER BY product_varient.id DESC";
		// echo $sql;die();
		//$result=$this->client_db->query($sql);
		//return $result->result();
		$query = $this->client_db->query($sql,false); 
		if($query){
			if($query->num_rows() > 0)
			{
				foreach ($query->result() as $row) 
				{          
					$vendor_list=array();
					// $sql2="SELECT t2.* 
					// 	FROM vendor_productvarient_tag AS t1
					// 	INNER JOIN vendor AS t2 ON t1.vendor_id=t2.id  
					// 	WHERE t1.product_varient_id='".$row->id."' 
					// 	ORDER BY t2.contact_person";
					
					$sql2="SELECT 
							t1.id,
							t1.vendor_id,
							t1.product_varient_id,
							t1.delivery_time,
							t1.delivery_time_unit,
							t1.price,
							t1.currency_type,
							t1.unit,
							t1.unit_type,
							t1.created_datetime,
							t1.updated_datetime,
							t2.name AS curr_name,
							t2.code AS curr_code,
							t3.type_name AS unit_type_name,
							t4.company_name AS vendor_company_name,
							t4.email AS vendor_email,
							t4.mobile AS vendor_mobile,
							t4.contact_person AS vendor_contact_person
							FROM vendor_productvarient_tag AS t1        	
							LEFT JOIN currency AS t2 ON t1.currency_type=t2.id
							LEFT JOIN unit_type AS t3 ON t1.unit_type=t3.id
							INNER JOIN vendor AS t4 ON t1.vendor_id=t4.id 
							WHERE t1.is_deleted='N' AND t1.product_varient_id='".$row->id."' ORDER BY t1.id DESC"; 


					$result2=$this->client_db->query($sql2);
					$vendor_list=$result2->result();

					$result[] = (object)array(
									'id'=> $row->id,
									'parent_id'=> $row->parent_id,
									'name'=> $row->name,
									'currency_type'=> $row->currency_type,
									'unit_type'=> $row->unit_type,
									'description'=> $row->description,
									'code'=> $row->code,
									'attachment'=> $row->attachment,
									'price'=>$row->price,
									'unit'=> $row->unit,
									'status'=> $row->status,
									'date_modified'=>$row->date_modified,
									'date_added'=>$row->date_added,
									'file_name'=>$row->file_name,
									'product_id'=>$row->product_id,
									'currency_type_name'=>$row->currency_type_name,
									'unit_type_name'=>$row->unit_type_name,
									'currency_type_code'=>$row->currency_type_code,
									'vendor_count'=>count($vendor_list),
									'vendor_list'=>$vendor_list
									);
					}
					return $result;
			}
			else
			{
				return array();
			}
		}
		else{
			return array();
		}
		

	}

	public function GetProductListNotFilter($id)
	{

		$sql="SELECT 
				product_varient.*,
				product_images.file_name, 
				product_varient.id AS product_id, 
				product_varient.name AS name, 
				currency.name AS currency_type_name, 
				unit_type.type_name AS unit_type_name, 
				currency.code AS currency_type_code 
				FROM product_varient  
				LEFT JOIN product_images ON product_varient.id=product_images.varient_id 
				LEFT JOIN currency ON product_varient.currency_type=currency.id 
				LEFT JOIN unit_type on product_varient.unit_type=unit_type.id where";
		if($id)
		{
			$sql.=" product_varient.id NOT IN (".$id.") and";
		}
		$sql.=" product_varient.status='0' GROUP BY product_images.varient_id ORDER BY product_varient.id DESC";
		
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}
	public function get_product_ids_by_opportunity_id($opp_id)
	{
		$arr=array();
		$sql="SELECT product_id FROM opportunity_product  WHERE opportunity_id='".$opp_id."'";
		$result=$this->client_db->query($sql);
		foreach($result->result_array() as $row)
		{
			array_push($arr, $row['product_id']);
		}
		return $arr;
	}
	

	public function GetProductData($id)
	{
		$sql="SELECT * FROM product_varient where `id`='".$id."' and status='0'";
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	public function GetProductImageList($id)
	{
		$sql="SELECT * FROM product_images where `varient_id`='".$id."'";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
	
	public function GetUnitList($client_info=array())
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
		
		$sql="SELECT id,type_name FROM unit_type ORDER BY type_name";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
	public function GetCurrencyList()
	{
		$sql="SELECT id,name,code FROM currency";
		$result=$this->client_db->query($sql);
		if($result)
		{
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}
	
	
	public function GetProductPDFList($id)
	{
		$sql="SELECT * FROM product_pdf where `varient_id`='".$id."'";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
	
	public function GetProductPDFData($id)
	{
		$sql="SELECT * FROM product_pdf where `varient_id`='".$id."'";
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	public function GetProductImageData($id)
	{
		$sql="SELECT * FROM product_images where `varient_id`='".$id."'";
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	public function CreateProduct($data)
	{

		if($this->client_db->insert('product',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function CreateProductVarient($data)
	{

		if($this->client_db->insert('product_varient',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
			// echo $last_query = $this->client_db->last_query();  die('ok');
          return false;
   		}

	}
	
	public function UpdateProductVarient($data,$id)
	{
		$this->client_db->where('id',$id);

		if($this->client_db->update('product_varient',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}
	
	public function DeleteProductVarient($data,$id)
	{
		$this->client_db->where('id',$id);
		$this->client_db->or_where('parent_id',$id);

		if($this->client_db->update('product_varient',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}
	
	public function DeleteVarient($data,$id)
	{
		$this->client_db->where('id',$id);
		
		if($this->client_db->update('product_varient',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}
	
	
	public function CreateProductVendor($data)
	{

		if($this->client_db->insert('product_vendor',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function DeleteProductVendor($product_id)
	{
		$this->client_db->where('product_id',$product_id);

		if($this->client_db->delete('product_vendor'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function CreateLeadProduct($data)
	{

		if($this->client_db->insert('lead_product',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	
	public function CreateProductImage($data)
	{
		if($this->client_db->insert('product_images',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function CreateProductPDF($data)
	{
		if($this->client_db->insert('product_pdf',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}

	public function DeleteProductPDF($pid)
	{
		$this->client_db->where('varient_id',$pid);
		if($this->client_db->delete('product_pdf'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	


	public function UpdateProduct($data,$id)
	{
		$this->client_db->where('id',$id);

		if($this->client_db->update('product',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}
	
	

	public function DeleteAttribute($name)
	{
		$this->client_db->where('name',$name);

		if($this->client_db->delete('product_attribute'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	public function RemoveImage($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('product_images'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function RemovePdf($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('product_pdf'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function GetAttributeData($name)
	{
		$sql="SELECT id, name, GROUP_CONCAT(`value` SEPARATOR ',') as value FROM product_attribute where name='".$name."' GROUP BY `name` ";
		$result=$this->client_db->query($sql);
		
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}

	}
	
	public function CreateTempProduct($data,$client_info=array())
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
		
		if($this->client_db->insert('temp_selected_product',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function GetTempProductList($user_id,$client_info=array())
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
		
		$sql="SELECT 
				temp_selected_product.id,
				temp_selected_product.opportunity_id,
				temp_selected_product.user_id,
				temp_selected_product.product_id,
				temp_selected_product.name,
				temp_selected_product.unit,
				temp_selected_product.unit_type,
				temp_selected_product.quantity,
				temp_selected_product.price,
				temp_selected_product.currency_type,
				temp_selected_product.discount,
				temp_selected_product.gst,
				temp_selected_product.create_date,
				product_varient.code,
				currency.name as currency_type_name,
				currency.name as currency_type_name,
				unit_type.type_name as unit_type_name, 
				currency.code AS currency_type_code 
				FROM temp_selected_product 
				left join product_varient on temp_selected_product.product_id=product_varient.id 
				left join currency on temp_selected_product.currency_type=currency.id 
				left join unit_type on temp_selected_product.unit_type=unit_type.id 
				where temp_selected_product.user_id='".$user_id."' ORDER BY temp_selected_product.id";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function GetTempProduct($user_id,$pid)
	{
		
		$sql="SELECT temp_selected_product.*,currency.name as currency_type_name,currency.name as currency_type_name,unit_type.type_name as unit_type_name, currency.code AS currency_type_code FROM temp_selected_product left join currency on temp_selected_product.currency_type=currency.id left join unit_type on temp_selected_product.unit_type=unit_type.id where temp_selected_product.user_id='".$user_id."' AND temp_selected_product.product_id='".$pid."' ORDER BY temp_selected_product.create_date DESC";
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	public function GetTempAndOppProductList($user_id,$opportunity_id)
	{
		
		
		/*SELECT product_varient.*,product_images.file_name, product.id AS product_id, product.name AS name, currency.name AS currency_type_name, unit_type.type_name AS unit_type_name FROM product LEFT JOIN product_varient ON product_varient.product_id=product.id LEFT JOIN product_images ON product_varient.id=product_images.varient_id LEFT JOIN currency ON product_varient.currency_type=currency.id LEFT JOIN unit_type on product_varient.unit_type=unit_type.id*/
		
		
		$sql="SELECT 
				product_varient.name as name,
				op.id as id,op.product_id as product_id,
				op.unit as unit,
				op.unit_type as unit_type,
				op.price as price,
				op.currency_type as currency_type,
				op.discount as discount,
				currency.name as currency_type_name,
				currency.code as currency_type_code,
				unit_type.type_name as unit_type_name, 
				currency.code AS currency_type_code 
				FROM `opportunity_product` AS op 
				INNER JOIN product_varient on product_varient.id = op.product_id 
				left join currency on op.currency_type=currency.id 
				left join unit_type on op.unit_type=unit_type.id  
				WHERE op.opportunity_id='".$opportunity_id."' and product_varient.status='0' 
				UNION ALL 
				SELECT product_varient.name as name,
				tp.id as id,
				tp.product_id as product_id,
				tp.unit as unit,
				tp.unit_type as unit_type,
				tp.price as price,
				tp.currency_type as currency_type,
				tp.discount as discount,
				currency.name as currency_type_name,
				currency.code as currency_type_code,
				unit_type.type_name as unit_type_name, 
				currency.code AS currency_type_code 
				FROM `temp_selected_product` AS tp 
				INNER JOIN product_varient on product_varient.id = tp.product_id 
				left join currency on tp.currency_type=currency.id 
				left join unit_type on tp.unit_type=unit_type.id 
				WHERE tp.user_id='".$user_id."' and product_varient.status='0'";
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	
	
	public function GetOpportunityProductList($opportunity_id)
	{
		
		$sql="SELECT product_varient.name as name,op.id as id,op.product_id as product_id,op.unit as unit,op.unit_type as unit_type,op.price as price,op.currency_type as currency_type,op.discount as discount,currency.name as currency_type_name,currency.name as currency_type_name,unit_type.type_name as unit_type_name, currency.code AS currency_type_code FROM `opportunity_product` AS op INNER JOIN product_varient on product_varient.id = op.product_id left join currency on op.currency_type=currency.id left join unit_type on op.unit_type=unit_type.id WHERE op.opportunity_id='".$opportunity_id."' and product_varient.status='0'";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}
	
	public function DeleteTempProduct($product_id,$user_id,$client_info=array())
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
		
		if($product_id!=''){
			$this->client_db->where('product_id',$product_id);
		}
		$this->client_db->where('user_id',$user_id);

		if($this->client_db->delete('temp_selected_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function Delete_temp_product()
	{
		if($this->client_db->delete('temp_selected_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function DeleteTempProductByUser($user_id)
	{
		
		$this->client_db->where('user_id',$user_id);

		if($this->client_db->delete('temp_selected_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function UpdateTempProduct($quantity,$price,$discount,$product_id,$user_id)
	{
		$this->client_db->where('product_id',$product_id);
		$this->client_db->where('user_id',$user_id);
		$data=array('unit'=>$quantity,'price'=>$price,'discount'=>$discount);

		if($this->client_db->update('temp_selected_product',$data))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function UpdateTempProductOnChangeAjax($data_post,$product_id,$user_id)
	{
		$this->client_db->where('product_id',$product_id);
		$this->client_db->where('user_id',$user_id);
		if($this->client_db->update('temp_selected_product',$data_post))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function TempProdExistCheck($id,$user_id,$client_info=array())
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

		$sql="SELECT 
		id,
		opportunity_id,
		user_id,
		product_id,
		name,
		unit,
		unit_type,
		price,
		currency_type,
		discount,
		gst,
		create_date
		FROM temp_selected_product 
		where `product_id`='".$id."' 
		and `user_id`='".$user_id."'";
		$result=$this->client_db->query($sql);
		//return $result->row();

		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}


	// ============================================
	// ADDED BY ARUP KUMAR POREL
	// ON 2018/01/11
	public function get_product_notTaggedInVendor($vid)
	{

		$sql="SELECT product_varient.*,
			  product_images.file_name, 
			  product_varient.id AS product_id, 
			  product_varient.name AS name, 
			  currency.name AS currency_type_name, 
			  unit_type.type_name AS unit_type_name, 
			  currency.code AS currency_type_code 
			  FROM product_varient  
			  LEFT JOIN product_images ON product_varient.id=product_images.varient_id 
			  LEFT JOIN currency ON product_varient.currency_type=currency.id 
			  LEFT JOIN unit_type on product_varient.unit_type=unit_type.id 
			  WHERE product_varient.status='0' AND product_varient.id NOT IN (SELECT product_varient_id from vendor_productvarient_tag WHERE vendor_id='".$vid."') AND is_deleted='N'  ORDER BY product_varient.id DESC";		
		
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_product_taggedInVendor($vid)
	{

		$sql="SELECT product_varient.*,
			  product_images.file_name, 
			  product_varient.id AS product_id, 
			  product_varient.name AS name, 
			  currency.name AS currency_type_name, 
			  unit_type.type_name AS unit_type_name, 
			  currency.code AS currency_type_code 
			  FROM product_varient  
			  LEFT JOIN product_images ON product_varient.id=product_images.varient_id 
			  LEFT JOIN currency ON product_varient.currency_type=currency.id 
			  LEFT JOIN unit_type on product_varient.unit_type=unit_type.id 
			  WHERE product_varient.status='0' AND product_varient.id IN (SELECT product_varient_id from vendor_productvarient_tag WHERE vendor_id='".$vid."') AND is_deleted='N' GROUP BY product_varient.id  ORDER BY product_varient.id DESC ";		
		
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}


	// AJAX SEARCH START
    function get_product_wise_vendor_list_count($argument=array())
    {           
        $subsql = '';    

        // ---------------------------------------
        // SEARCH VALUE               
         if($argument['search_existing_product_id']!='')
        {
            $subsql .= " AND t1.product_varient_id='".$argument['search_existing_product_id']."'";
        }       
        // SEARCH VALUE
        // ---------------------------------------

        $sql="SELECT t1.*
        	FROM vendor_productvarient_tag AS t1 
        	LEFT JOIN currency AS t2 ON t1.currency_type=t2.id
        	LEFT JOIN unit_type AS t3 ON t1.unit_type=t3.id
        	INNER JOIN vendor AS t4 ON t1.vendor_id=t4.id 
        	WHERE t1.is_deleted='N' $subsql";

        $query = $this->client_db->query($sql,false);     
        if($query->num_rows() > 0) {
            return $query->num_rows();
        }
        else {
            return 0;
        }
    }

    function get_product_wise_vendor_list($argument=array())
    {
       
        $result = array();            
        $subsql = '';    
        $start=$argument['start'];
        $limit=$argument['limit'];

        // ---------------------------------------
        // SEARCH VALUE 
        if($argument['search_existing_product_id']!='')
        {
            $subsql .= " AND t1.product_varient_id='".$argument['search_existing_product_id']."'";
        }

        // SEARCH VALUE
        // ---------------------------------------

        $order_by=(isset($argument['order_by']))?$argument['order_by']:'DESC';
        $sql="SELECT t1.*,
        	t2.name AS curr_name,
        	t2.code AS curr_code,
        	t3.type_name AS unit_type_name,
        	t4.company_name AS vendor_company_name,
        	t4.email AS vendor_email,
        	t4.mobile AS vendor_mobile,
        	t4.contact_person AS vendor_contact_person
        	FROM vendor_productvarient_tag AS t1        	
        	LEFT JOIN currency AS t2 ON t1.currency_type=t2.id
        	LEFT JOIN unit_type AS t3 ON t1.unit_type=t3.id
        	INNER JOIN vendor AS t4 ON t1.vendor_id=t4.id 
        	WHERE t1.is_deleted='N' $subsql ORDER BY t1.created_datetime $order_by LIMIT $start,$limit"; 

        $query = $this->client_db->query($sql,false);        
        $last_query = $this->client_db->last_query();
        //return $query->result(); 
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}    
    }
	
	function get_product_wise_vendor_list_all($argument=array())
    {
       
        $result = array();            
        $subsql = '';
        $subsql .= " AND t1.product_varient_id='".$argument['product_id']."'";

        // SEARCH VALUE
        // ---------------------------------------

        $order_by=(isset($argument['order_by']))?$argument['order_by']:'DESC';
        $sql="SELECT t1.*,
        	t2.name AS curr_name,
        	t2.code AS curr_code,
        	t3.type_name AS unit_type_name,
        	t4.company_name AS vendor_company_name,
        	t4.email AS vendor_email,
        	t4.mobile AS vendor_mobile,
        	t4.contact_person AS vendor_contact_person
        	FROM vendor_productvarient_tag AS t1        	
        	LEFT JOIN currency AS t2 ON t1.currency_type=t2.id
        	LEFT JOIN unit_type AS t3 ON t1.unit_type=t3.id
        	INNER JOIN vendor AS t4 ON t1.vendor_id=t4.id 
        	WHERE t1.is_deleted='N' $subsql ORDER BY t1.created_datetime $order_by"; 

        $query = $this->client_db->query($sql,false);        
        $last_query = $this->client_db->last_query();
        //return $query->result(); 
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}    
    }
    // AJAX SEARCH END
	
	public function remove_already_tagged($pid)
	{		
		$this->client_db->where('product_varient_id', $pid);
		$this->client_db->delete('vendor_productvarient_tag');		
	}
    public function chk_already_tagged($vendor_id,$product_id)
    {
    	$sql="SELECT * FROM vendor_productvarient_tag 
    	WHERE vendor_id='".$vendor_id."' AND product_varient_id='".$product_id."' AND is_deleted='N'";
        $query = $this->client_db->query($sql,false);  
		if($query){
			return $query->num_rows();
		} else {
			return 0;
		}
    }

    // AJAX SEARCH START
    function get_list_count($argument=NULL)
    {
          
        $subsql = '';    
        // ---------------------------------------
        // SEARCH VALUE   
		if(isset($argument['not_in_ids']) && $argument['not_in_ids']!='')
        {
            $subsql .= " AND t1.id NOT IN (".$argument['not_in_ids'].")";
        }

		if($argument['filter_search_str']!='')
        {
            $subsql .= " AND  (t1.name LIKE '%".$argument['filter_search_str']."%' || t1.code='".$argument['filter_search_str']."')";
			$subsql .= " AND  t1.status='0'";
        }
		
        if($argument['filter_aproved']!='' && $argument['filter_disabled']=='')
        {
            $subsql .= " AND  t1.status = '0'";
        }
        else if($argument['filter_aproved']=='' && $argument['filter_disabled']!='')
        {
            $subsql .= " AND  t1.status = '1'";
        }
        else if($argument['filter_aproved']!='' && $argument['filter_disabled']!='')
        {
        	$subsql .= " AND  t1.status IN ('0','1')";
        }

        if($argument['filter_disabled']!='' && $argument['filter_disabled_reason']!='')
        {
            $subsql .= " AND  t1.disabled_reason = '".$argument['filter_disabled_reason']."'";
        }

		if($argument['filter_group_id']!='')
        {
            $subsql .= " AND  t1.group_id = '".$argument['filter_group_id']."'";
        }
		if($argument['filter_cate_id']!='')
        {
            $subsql .= " AND  t1.cate_id = '".$argument['filter_cate_id']."'";
        }
		
		
        if($argument['filter_with_image']!='')
        {
            if($argument['filter_with_image']=='Y'){
            	$subsql .= " AND  t4.file_name != ''";
        	}
            else if($argument['filter_with_image']=='N'){
            	$subsql .= " AND  t4.file_name IS NULL";
            }
            else{

            }
        }

        if($argument['filter_with_brochure']!='')
        {
        	if($argument['filter_with_brochure']=='Y'){
            	$subsql .= " AND  t5.file_name != ''";
        	}
            else if($argument['filter_with_brochure']=='N'){
            	$subsql .= " AND  t5.file_name IS NULL";
            }
            else{

            }            
            //$subsql .= " AND  t5.file_name != ''";
        }
        if($argument['filter_with_youtube_video']!='')
        {
        	if($argument['filter_with_youtube_video']=='Y'){
            	$subsql .= " AND  t1.youtube_video != ''";
        	}
            else if($argument['filter_with_youtube_video']=='N'){
            	$subsql .= " AND  t1.youtube_video = ''";
            }
            else{

            } 
            //$subsql .= " AND  t1.youtube_video != ''";
        }
        if($argument['filter_with_gst']!='')
        {
            $subsql .= " AND  t1.gst_percentage > '0'";
        }
        if($argument['filter_with_hsn_code']!='')
        {
            $subsql .= " AND  t1.hsn_code != ''";
        }

        if($argument['filter_product_available_for']!='')
        {      
        	$tmp_str='';
        	$tmp_arr=explode(",", $argument['filter_product_available_for']); 
        	foreach($tmp_arr as $val)
        	{
        		$tmp_str .="'".trim($val)."',";
        	}  
        	$tmp_str=rtrim($tmp_str,",");  
            $subsql .= " AND  t1.product_available_for IN (".$tmp_str.")";
        }

        if($argument['search_product']!='')
        {
            //$subsql .= " AND  t1.product_id = '".$argument['search_product']."'";
            $subsql .= " AND  t1.name LIKE '".$argument['search_product']."%'";
        }
		/*
        if($argument['status']!='')
        {
            $subsql .= " AND  t1.status = '".$argument['status']."'";
        }
		*/
        // SEARCH VALUE
        // ---------------------------------------
        $sql="SELECT t1.*
        FROM product_varient AS t1
        LEFT JOIN currency AS t2 ON t1.currency_type=t2.id
        LEFT JOIN unit_type AS t3 ON t1.unit_type=t3.id
        LEFT JOIN product_images AS t4 ON t1.id=t4.varient_id
        LEFT JOIN product_pdf AS t5 ON t1.id=t5.varient_id
        LEFT JOIN 
        (SELECT count(id) AS vendor_count,product_varient_id FROM `vendor_productvarient_tag` group by `product_varient_id` ) AS t6 ON t1.id=t6.product_varient_id
        WHERE t1.is_deleted= 'N' $subsql 
        GROUP BY t1.id";     

        $query = $this->client_db->query($sql,false);   
		if($query){
			if($query->num_rows() > 0) {
				return $query->num_rows();
			}
			else {
				return 0;
			}
		}  
		else{
			return 0;
		}
        
    }
    
    function get_list($argument=NULL)
    {
       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   

        $order_by_str = " ORDER BY  t1.id DESC ";
        if($argument['filter_sort_by']!='')
        {
			/*
        	if($argument['filter_sort_by']=='P_L_M')
            	$order_by_str = " ORDER BY  t1.date_modified DESC ";
            else if($argument['filter_sort_by']=='P_H_TO_L')
            	$order_by_str = " ORDER BY  t1.price DESC";
            else if($argument['filter_sort_by']=='P_L_TO_H')
            	$order_by_str = " ORDER BY  t1.price ASC";
			*/
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  replace(t1.$field_name , ' ','') ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE 

        if(isset($argument['not_in_ids']) && $argument['not_in_ids']!='')
        {
            $subsql .= " AND t1.id NOT IN (".$argument['not_in_ids'].")";
        }

        if($argument['filter_search_str']!='')
        {
            $subsql .= " AND  (t1.name LIKE '%".$argument['filter_search_str']."%' || t1.code='".$argument['filter_search_str']."')";
			$subsql .= " AND  t1.status='0'";
        }
		
        if($argument['filter_aproved']!='' && $argument['filter_disabled']=='')
        {
            $subsql .= " AND  t1.status = '0'";
        }
        else if($argument['filter_aproved']=='' && $argument['filter_disabled']!='')
        {
            $subsql .= " AND  t1.status = '1'";
        }
        else if($argument['filter_aproved']!='' && $argument['filter_disabled']!='')
        {
        	$subsql .= " AND  t1.status IN ('0','1')";
        }

        if($argument['filter_disabled']!='' && $argument['filter_disabled_reason']!='')
        {
            $subsql .= " AND  t1.disabled_reason = '".$argument['filter_disabled_reason']."'";
        }
        
		if($argument['filter_group_id']!='')
        {
            $subsql .= " AND  t1.group_id = '".$argument['filter_group_id']."'";
        }
		if($argument['filter_cate_id']!='')
        {
            $subsql .= " AND  t1.cate_id = '".$argument['filter_cate_id']."'";
        }
		
		
        if($argument['filter_with_image']!='')
        {
            if($argument['filter_with_image']=='Y'){
            	$subsql .= " AND  t4.file_name != ''";
        	}
            else if($argument['filter_with_image']=='N'){
            	$subsql .= " AND  t4.file_name IS NULL";
            }
            else{

            }
        }

        if($argument['filter_with_brochure']!='')
        {
        	if($argument['filter_with_brochure']=='Y'){
            	$subsql .= " AND  t5.file_name != ''";
        	}
            else if($argument['filter_with_brochure']=='N'){
            	$subsql .= " AND  t5.file_name IS NULL";
            }
            else{

            }            
            //$subsql .= " AND  t5.file_name != ''";
        }
        if($argument['filter_with_youtube_video']!='')
        {
        	if($argument['filter_with_youtube_video']=='Y'){
            	$subsql .= " AND  t1.youtube_video != ''";
        	}
            else if($argument['filter_with_youtube_video']=='N'){
            	$subsql .= " AND  t1.youtube_video = ''";
            }
            else{

            } 
            //$subsql .= " AND  t1.youtube_video != ''";
        }
        if($argument['filter_with_gst']!='')
        {
            $subsql .= " AND  t1.gst_percentage > '0'";
        }
        if($argument['filter_with_hsn_code']!='')
        {
            $subsql .= " AND  t1.hsn_code != ''";
        }

        if($argument['filter_product_available_for']!='')
        {      
        	$tmp_str='';
        	$tmp_arr=explode(",", $argument['filter_product_available_for']); 
        	foreach($tmp_arr as $val)
        	{
        		$tmp_str .="'".trim($val)."',";
        	}  
        	$tmp_str=rtrim($tmp_str,",");  
            $subsql .= " AND  t1.product_available_for IN (".$tmp_str.")";
        }

        if($argument['search_product']!='')
        {
            //$subsql .= " AND  t1.product_id = '".$argument['search_product']."'";
            $subsql .= " AND  t1.name LIKE '".$argument['search_product']."%'";
        }
		/*
        if($argument['status']!='')
        {
            $subsql .= " AND  t1.status = '".$argument['status']."'";
        }
		*/

        // SEARCH VALUE
        // ---------------------------------------

        $sql="SELECT 
        	t1.id,
        	t1.id AS product_id,
        	t1.parent_id,
        	t1.group_id,
        	t1.cate_id,
        	t1.name,
        	t1.currency_type,
        	t1.unit_type,
        	t1.description,
        	t1.long_description,
        	t1.code,
        	t1.attachment,
        	t1.price,
        	t1.unit,
        	t1.minimum_order_quantity,
        	t1.status,
        	t1.disabled_reason,
        	t1.disabled_reason_text,
        	t1.gst_percentage,
        	t1.hsn_code,
        	t1.youtube_video,
        	t1.product_available_for,
        	t1.product_added_by,
        	t1.product_last_modified_by,
        	t1.date_modified,
        	t1.date_added,
        	t2.name AS curr_name,t2.code AS curr_code,t3.type_name AS unit_type_name,
        GROUP_CONCAT(CONCAT(t4.id,'#',t4.file_name)) AS images,
        t5.file_name AS brochures,t6.vendor_count,t6.v_tag_str,
		t7.name AS group_name,t8.name AS cat_name		
        FROM product_varient AS t1
        LEFT JOIN currency AS t2 ON t1.currency_type=t2.id
        LEFT JOIN unit_type AS t3 ON t1.unit_type=t3.id
        LEFT JOIN product_images AS t4 ON t1.id=t4.varient_id
        LEFT JOIN product_pdf AS t5 ON t1.id=t5.varient_id
        LEFT JOIN 
        (
			SELECT 
				count(id) AS vendor_count,
				product_varient_id,
				GROUP_CONCAT(
						DISTINCT CONCAT('@',vendor_id,'_',price,'_',currency_type,'_',unit,'_',unit_type) 
						ORDER BY id 
						SEPARATOR '^'
				) AS v_tag_str
			FROM `vendor_productvarient_tag` group by `product_varient_id` 
		) AS t6 ON t1.id=t6.product_varient_id
		LEFT JOIN group_wise_category AS t7 ON t1.group_id=t7.id
		LEFT JOIN group_wise_category AS t8 ON t1.cate_id=t8.id		
        WHERE t1.is_deleted= 'N' $subsql 
        GROUP BY t1.id $order_by_str 
        LIMIT $start,$limit";         
        $query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();die();
        //return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
    }
	// ADDED BY ARUP KUMAR POREL
	// ============================================

	public function searchProducs($search_data,$client_info=array()){

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

		$sql="SELECT product_varient.*,product_images.file_name,product_varient.id AS product_id, 
		product_varient.name AS name,currency.name AS currency_type_name,currency.code AS currency_type_code, 
		unit_type.type_name AS unit_type_name,product_pdf.file_name as brochure 
		FROM product_varient 
		LEFT JOIN product_images ON product_varient.id=product_images.varient_id 
		LEFT JOIN currency ON product_varient.currency_type=currency.id 
		LEFT JOIN unit_type on product_varient.unit_type=unit_type.id 
		LEFT JOIN product_pdf ON product_varient.id=product_pdf.varient_id  
		WHERE product_varient.parent_id='0' 
		AND product_varient.status='0' AND product_varient.is_deleted='N'";
		if($search_data)
			$sql.="AND product_varient.name LIKE '%".$search_data."%'";
		$sql.="ORDER BY product_varient.id DESC";
		$result=$this->client_db->query($sql);
		if($result->num_rows()){
			return $result->result_array();
		}else{
			return array();
		}
	}

	public function getProducts($type){
		if($type=='count'){
			$this->client_db->where(['parent_id'=>'0','status'=>'0']);
			$result = $this->client_db->get('product_varient');
			if($result->num_rows()){
				return $result->num_rows();
			}else{
				return 0;
			}
		}

		$sql="SELECT product_varient.*,product_images.file_name,product_varient.id AS product_id, 
		product_varient.name AS name,currency.name AS currency_type_name,currency.code AS currency_type_code, 
		unit_type.type_name AS unit_type_name,product_pdf.file_name as brochure 
		FROM product_varient 
		LEFT JOIN product_images ON product_varient.id=product_images.varient_id 
		LEFT JOIN currency ON product_varient.currency_type=currency.id 
		LEFT JOIN unit_type on product_varient.unit_type=unit_type.id 
		LEFT JOIN product_pdf ON product_varient.id=product_pdf.varient_id  
		WHERE product_varient.parent_id='0' 
		AND product_varient.status='0' ORDER BY product_varient.id DESC";
		$result=$this->client_db->query($sql);
		if($result->num_rows()){
			return $result->result_array();
		}else{
			return array();
		}
	}

	public function getSinglePriceProducts($type){
		if($type=='count'){
			$sql="SELECT t1.* FROM customer AS t1 
			INNER JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.current_stage='DEAL WON' AND t2.status='1'
			WHERE t1.status='1' GROUP BY t1.id ORDER BY t1.id DESC";
			$result=$this->client_db->query($sql);
			return $result->num_rows();
		}else{
			$sql="SELECT t1.*,COUNT(t2.id) AS lead_count,t3.name AS assigned_user_name,
			t4.name AS city_name,t5.name AS country_name,SUM(if(t2.current_stage_id=4,1,0)) AS lead_won_count 
			FROM customer AS t1 
			INNER JOIN lead AS t2 ON t1.id=t2.customer_id AND t2.current_stage='DEAL WON' AND t2.status='1'
			LEFT JOIN user AS t3 ON t2.assigned_user_id=t3.id
			LEFT JOIN cities AS t4 ON t1.city=t4.id
			LEFT JOIN countries AS t5 ON t1.country_id=t5.id			
			WHERE t1.status='1' GROUP BY t1.id ORDER BY t1.id DESC";
			$result=$this->client_db->query($sql);
			//return $result->result();

			if($result){
				return $result->result();
			}
			else{
				return (object)array();
			}
		}
	}

	public function getVendosProducts($type){
		if($type=='count'){
			$sql="SELECT count(DISTINCT product_varient_id) as vendor_count FROM vendor_productvarient_tag
			INNER JOIN product_varient ON vendor_productvarient_tag.product_varient_id=product_varient.id
			WHERE product_varient.parent_id='0' AND product_varient.status='0'";
			$result=$this->client_db->query($sql);
			if($result->num_rows()){
				return $result->row()->vendor_count;
			}else{
				return 0;
			}
		}else{
			$sql="SELECT product_varient.*,product_images.file_name,product_varient.id AS product_id, 
			product_varient.name AS name, currency.name AS currency_type_name, 
			currency.code AS currency_type_code, unit_type.type_name AS unit_type_name
			FROM vendor_productvarient_tag
			INNER JOIN product_varient ON vendor_productvarient_tag.product_varient_id=product_varient.id
			LEFT JOIN product_images ON vendor_productvarient_tag.product_varient_id=product_images.varient_id
			LEFT JOIN currency ON product_varient.currency_type=currency.id 
			LEFT JOIN unit_type on product_varient.unit_type=unit_type.id  
			WHERE product_varient.parent_id='0' AND
			product_varient.status='0' GROUP BY vendor_productvarient_tag.product_varient_id ORDER BY product_varient.id DESC";
			$result=$this->client_db->query($sql);
			if($result->num_rows()){
				return $result->result_array();
			}else{
				return array();
			}
		}
	}

	public function getPhotoProducts($type){
		if($type=='count'){
			$sql="SELECT count(DISTINCT varient_id) as img_count FROM product_images
			INNER JOIN product_varient ON product_images.varient_id=product_varient.id
			WHERE product_varient.parent_id='0' AND product_varient.status='0'";
			$result=$this->client_db->query($sql);
			if($result->num_rows()){
				return $result->row()->img_count;
			}else{
				return 0;
			}
		}else{
			$sql="SELECT product_varient.*,product_images.file_name,product_varient.id AS product_id, 
			product_varient.name AS name, currency.name AS currency_type_name, 
			currency.code AS currency_type_code, unit_type.type_name AS unit_type_name
			FROM product_images 
			INNER JOIN product_varient ON product_images.varient_id=product_varient.id
			LEFT JOIN currency ON product_varient.currency_type=currency.id 
			LEFT JOIN unit_type on product_varient.unit_type=unit_type.id  
			WHERE product_varient.parent_id='0' AND
			product_varient.status='0' GROUP BY product_images.varient_id ORDER BY product_varient.id DESC";
			$result=$this->client_db->query($sql);
			if($result->num_rows()){
				return $result->result_array();
			}else{
				return array();
			}
		}
	}

	public function getBrochureProducts($type){
		if($type=='count'){
			$sql="SELECT count(DISTINCT varient_id) as pdf_count FROM product_pdf
			INNER JOIN product_varient ON product_pdf.varient_id=product_varient.id
			WHERE product_varient.parent_id='0' AND product_varient.status='0'";
			$result=$this->client_db->query($sql);
			if($result->num_rows()){
				return $result->row()->pdf_count;
			}else{
				return 0;
			}
		}else{
			$sql="SELECT product_varient.*,product_images.file_name,product_varient.id AS product_id, 
			product_varient.name AS name, currency.name AS currency_type_name, 
			currency.code AS currency_type_code, unit_type.type_name AS unit_type_name
			FROM product_pdf 
			INNER JOIN product_varient ON product_pdf.varient_id=product_varient.id
			LEFT JOIN product_images ON product_pdf.varient_id=product_images.varient_id
			LEFT JOIN currency ON product_varient.currency_type=currency.id 
			LEFT JOIN unit_type on product_varient.unit_type=unit_type.id  
			WHERE product_varient.parent_id='0' AND
			product_varient.status='0' GROUP BY product_pdf.varient_id ORDER BY product_varient.id DESC";
			$result=$this->client_db->query($sql);
			if($result->num_rows()){
				return $result->result_array();
			}else{
				return array();
			}
		}
	}

	function get_details($id=NULL)
    {
       
        $result = array();
        $sql="SELECT t1.*,t1.id AS product_id,t2.name AS curr_name,t2.code AS curr_code,t3.type_name AS unit_type_name,GROUP_CONCAT(DISTINCT t4.file_name) AS images,GROUP_CONCAT(t5.file_name) AS brochures,
		t6.name AS group_name,
		t7.name AS cate_name
		FROM product_varient AS t1
        LEFT JOIN currency AS t2 ON t1.currency_type=t2.id
        LEFT JOIN unit_type AS t3 ON t1.unit_type=t3.id
        LEFT JOIN product_images AS t4 ON t1.id=t4.varient_id
        LEFT JOIN product_pdf AS t5 ON t1.id=t5.varient_id
		LEFT JOIN group_wise_category AS t6 ON t1.group_id=t6.id
		LEFT JOIN group_wise_category AS t7 ON t1.cate_id=t7.id
        WHERE t1.is_deleted= 'N' AND t1.id='".$id."'";         
        $query = $this->client_db->query($sql,false);        
        //$last_query = $this->client_db->last_query();
        //return $query->row();
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
    }

    public function getProductsCount()
    {
		$sql="SELECT COUNT(*) AS total_count,
		SUM(if(status=0,1,0)) AS approved_count,
		SUM(if(status=1,1,0)) AS disabled_count	
		FROM product_varient WHERE is_deleted='N'";
		$query = $this->client_db->query($sql,false);        
        //$last_query = $this->client_db->last_query();
        //return $query->row_array();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	public function GetDisabledReasonKeyVal()
	{
		$result = array();           
		$subsql = '';      
		$subsql .= "";
		$sql="SELECT * FROM product_disabled_reason WHERE is_active='Y' $subsql ORDER BY title";         
		$query = $this->client_db->query($sql,false);        
		$last_query = $this->client_db->last_query();  
		if($query){
			foreach ($query->result() as $row) 
			{               
			$result[$row->id] = $row->title;
			}
		}      
		
		return $result;   
	}
	
	function get_list_for_bulk_update($argument=NULL)
    {       
        $result = array();        
        $subsql = '';
        $order_by_str = " ORDER BY  t1.id DESC ";
        // ---------------------------------------
        // SEARCH VALUE        
        if($argument['filter_by']!='')
        {
			$action=$argument['filter_by'];
			if($action=='with_gst')
			{
				$subsql .= " AND  t1.gst_percentage > '0'";
			}
			else if($action=='without_gst')
			{
				$subsql .= " AND  t1.gst_percentage = '0'";
			}
			else if($action=='with_code')
			{
				$subsql .= " AND  t1.code != ''";
			}
			else if($action=='without_code')
			{
				$subsql .= " AND  t1.code = ''";
			}
			else if($action=='with_hsn')
			{
				$subsql .= " AND  t1.hsn_code != ''";
			}
			else if($action=='without_hsn')
			{
				$subsql .= " AND  t1.hsn_code = ''";
			}
			else if($action=='with_price')
			{
				$subsql .= " AND  t1.price > '0'";
			}
			else if($action=='without_price')
			{
				$subsql .= " AND  t1.price = '0'";
			}
            
        }
        // SEARCH VALUE
        // ---------------------------------------

        $sql="SELECT t1.*,t1.id AS product_id,t2.name AS curr_name,t2.code AS curr_code,t3.type_name AS unit_type_name,
        GROUP_CONCAT(CONCAT(t4.id,'#',t4.file_name)) AS images,
        t5.file_name AS brochures,t6.vendor_count,t7.name AS group_name,t8.name AS cat_name
        FROM product_varient AS t1
        LEFT JOIN currency AS t2 ON t1.currency_type=t2.id
        LEFT JOIN unit_type AS t3 ON t1.unit_type=t3.id
        LEFT JOIN product_images AS t4 ON t1.id=t4.varient_id
        LEFT JOIN product_pdf AS t5 ON t1.id=t5.varient_id
        LEFT JOIN 
        (SELECT count(id) AS vendor_count,product_varient_id FROM `vendor_productvarient_tag` group by `product_varient_id` ) AS t6 ON t1.id=t6.product_varient_id
		LEFT JOIN group_wise_category AS t7 ON t1.group_id=t7.id
		LEFT JOIN group_wise_category AS t8 ON t1.cate_id=t8.id
        WHERE t1.is_deleted= 'N' $subsql 
        GROUP BY t1.id $order_by_str";         
        $query = $this->client_db->query($sql,false);        
        $last_query = $this->client_db->last_query();
        //return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
    }

    public function list_product_name()
    {
		$sql="SELECT id,name FROM product_varient 
		WHERE status='0' AND is_deleted='N'";
		$result=$this->client_db->query($sql);			
		//return $result->result_array();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	public function GetOnlyProductList($search_str='')
	{
		$subsql="";
		if($search_str)
		{
			$subsql .=" AND t1.name LIKE '%".$search_str."%'";
		}
		$sql="SELECT 
			t1.id,
			t1.name 
			FROM product_varient AS t1  WHERE t1.status='0' AND t1.is_deleted='N' $subsql
			ORDER BY t1.name LIMIT 0,10";	
		$query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();die();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
        
	}

	public function get_product($id,$client_info=array())
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
		$sql="SELECT 
		product_varient.id,
		product_varient.name,
		product_varient.code,
		product_varient.price,
		product_varient.unit,
		product_varient.description,
		product_varient.hsn_code,
		currency.name AS currency_name,
		currency.code AS currency_code,
		unit_type.type_name AS unit_type_name 
		FROM product_varient 
		LEFT JOIN currency on product_varient.currency_type=currency.id 
		LEFT JOIN unit_type on product_varient.unit_type=unit_type.id 
		WHERE product_varient.id='".$id."' 
		AND product_varient.status='0' AND product_varient.is_deleted='N'";
		// echo $sql; die();
		$result=$this->client_db->query($sql);
		//return $result->row_array();
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	public function get_product_ids_by_quotation_id($q_id)
	{
		$arr=array();
		$sql="SELECT product_id FROM quotation_product WHERE quotation_id='".$q_id."'";
		$result=$this->client_db->query($sql);
		if($result){
			foreach($result->result_array() as $row)
			{
				array_push($arr, $row['product_id']);
			}
		}
		
		return $arr;
	}

	public function GetCurrencyListForQuotationPopup($client_info=array())
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
		$sql="SELECT id,code FROM currency";
		$result=$this->client_db->query($sql);
		if($result)
		{
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_category_info_by_id($id='')
	{		
		if($id){			
			$sql="SELECT id,parent_id FROM group_wise_category WHERE id='".$id."' AND is_active='Y'";
			$query=$this->client_db->query($sql);	
			if($query){
				if($query->num_rows()>0)
				{
					$row=$query->row();
					return array('id'=>$row->id,'group_id'=>$row->parent_id);
				}
				else
				{
					return array();
				}
			}	
			else{
				return array();
			}
		}
		else{
			return array();
		}
				
	}

	public function get_category_info_by_name($text='')
	{		
		if($text){
			$text=str_replace(' ', '', strtolower($text));
			$sql="SELECT id,parent_id FROM group_wise_category WHERE replace(LOWER(name) , ' ','')='".$text."' AND is_active='Y' LIMIT 1";
			$query=$this->client_db->query($sql);	
			if($query){
				if($query->num_rows()>0)
				{
					$row=$query->row();
					return array('id'=>$row->id,'group_id'=>$row->parent_id);
				}
				else
				{
					return array();
				}
			}	
			else{
				return array();
			}
		}
		else{
			return array();
		}
				
	}
	
	public function get_currency_id_by_code($text)
	{		
		if($text){
			$text=str_replace(' ', '', strtolower($text));
			$sql="SELECT id FROM currency WHERE replace(LOWER(code) , ' ','')='".$text."' LIMIT 1";
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
		else{
			return 0;
		}				
	}

	public function get_unit_type_id_by_name($text)
	{		
		if($text){
			$text=str_replace(' ', '', strtolower($text));
			$sql="SELECT id FROM unit_type WHERE replace(LOWER(type_name) , ' ','')='".$text."' LIMIT 1";
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
		else{
			return 0;
		}				
	}

	public function add_csv_upload_tmp($data)
	{
		if($this->client_db->insert('product_varient_csv_upload_tmp',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function csv_upload_tmp_data_chk($user_id)
	{
		$sql="SELECT 
		tmp_id,
		group_id,
		cate_id,
		name,
		currency_type,	
		unit_type,	
		description,
		price,
		unit,
		minimum_order_quantity,
		product_available_for  
		FROM product_varient_csv_upload_tmp AS t1 WHERE t1.uploaded_by='".$user_id."' ORDER BY t1.tmp_id";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        $error_log=[];
        if($query->num_rows() > 0) 
        {
			// ========================================================
			$cat_arr=[];
			$get_cat=$this->GetProductCategoryList(); 
			if(count($get_cat)){
				foreach($get_cat AS $cat){
					$cat_name_tmp=$cat['id'];
					array_push($cat_arr, $cat_name_tmp);
				}
			}		
			
			// ========================================================

			// ========================================================
			$currency_arr=[];
            $get_currency=$this->GetCurrencyList();
			if(count($get_currency)){
				foreach($get_currency AS $currency){
					$currency_name_tmp=strtolower(str_replace(' ', '', $currency->code));
					array_push($currency_arr, $currency_name_tmp);
				}  
			}
			// ========================================================
			  
			// ========================================================
			$ut_arr=[];
            $get_ut=$this->GetProductUnitTypeList();
			if(count($get_ut)){
				foreach($get_ut AS $ut){
					$ut_name_tmp=strtolower(str_replace(' ', '', $ut['name']));
					array_push($ut_arr, $ut_name_tmp);
				}
			}
			// ========================================================
			

        	$rows=$query->result();
        	foreach($rows AS $row)
        	{
        		if($row->cate_id=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'category',
        										'msg'=>'Missing category'
        										);
        		}
				else
        		{            		          		
            		if(count($cat_arr))
            		{            			
            			if (!in_array($row->cate_id, $cat_arr))
	        			{
	        				$error_log[$row->tmp_id][]=array('keyword'=>'category',
	        										'msg'=>'category Id not found in the system'
	        										);
	        			}
            		}
        		}

        		if($row->name=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'name',
        										'msg'=>'Missing name'
        										);
        		}

        		if($row->currency_type=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'currency_type',
        										'msg'=>'Missing currency_type'
        										);
        		}  
        		else
        		{
        			        		           		
            		if(count($currency_arr))
            		{            			
            			if (!in_array(strtolower(str_replace(' ', '', $row->currency_type)), $currency_arr))
	        			{
	        				$error_log[$row->tmp_id][]=array('keyword'=>'currency_type',
	        										'msg'=>'currency_type not found in the system'
	        										);
	        			}
            		}
        		}

				if($row->unit_type=='')
        		{
        			$error_log[$row->tmp_id][]=array('keyword'=>'unit_type',
        										'msg'=>'Missing unit_type'
        										);
        		}  
        		else
        		{        			            		         		
            		if(count($ut_arr))
            		{            			
            			if (!in_array(strtolower(str_replace(' ', '', $row->unit_type)), $ut_arr))
	        			{
	        				$error_log[$row->tmp_id][]=array('keyword'=>'unit_type',
	        										'msg'=>'unit_type not found in the system'
	        										);
	        			}
            		}
        		}
				
				// if($row->description=='')
        		// {
        		// 	$error_log[$row->tmp_id][]=array('keyword'=>'description',
        		// 								'msg'=>'Missing description'
        		// 								);
        		// }

				// if($row->price=='')
        		// {
        		// 	$error_log[$row->tmp_id][]=array('keyword'=>'price',
        		// 								'msg'=>'Missing price'
        		// 								);
        		// }
        	}        	
        }
        return $error_log;       
	}

	public function GetProductCategoryList()
	{
		$sql="SELECT 
		t1.id,
		t1.name
		FROM group_wise_category AS t1 
		WHERE t1.parent_id>'0' GROUP BY t1.id ORDER BY t1.parent_id,t1.id";
		$result=$this->client_db->query($sql);		
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}		
	}

	public function GetProductUnitTypeList()
	{
		$sql="SELECT 
		t1.id,
		t1.type_name AS name,
		if(COUNT(t2.id)>0,'Y','N') AS is_product_tagged
		FROM unit_type AS t1 
		LEFT JOIN product_varient AS t2 ON t1.id=t2.unit_type	 		
		WHERE 1 GROUP BY t1.id ORDER BY t1.id DESC";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();	
		}
		else{
			return array();
		}	
	}

	public function delete_lead_csv_upload_tmp_by_user($user_id)
	{
		$this->client_db -> where('uploaded_by', $user_id);
    	$this->client_db -> delete('product_varient_csv_upload_tmp');
	}

	public function get_csv_upload_tmp_list($user_id)
	{
		$sql="SELECT tmp_id,
		group_id,
		cate_id,
		name,
		currency_type,
		unit_type,
		description,
		code,
		price,
		unit,
		minimum_order_quantity,
		gst_percentage,
		hsn_code,
		youtube_video,
		product_available_for,
		uploaded_datetime,
		uploaded_by 
		FROM product_varient_csv_upload_tmp WHERE uploaded_by='".$user_id."' ORDER BY tmp_id";
		$query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();
        //return $query->result_array();

		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	function update_csv_upload_tmp($data,$id)
	{
		$this->client_db->where('tmp_id',$id);
		if($this->client_db->update('product_varient_csv_upload_tmp',$data))
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