<?php
class Vendor_model extends CI_Model
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

	public function GetVendorListAll()
	{
		//$this->client_db->from('vendor');	
		//$this->client_db->where('status','0');
		//$result=$this->client_db->get();
		//return $result->result();

		$sql = "SELECT v.*,vt.*,cnt.name AS country_name,st.name AS state_name,c.name AS city_name
				FROM vendor AS v 
				INNER JOIN countries AS cnt ON v.country_id=cnt.id 
				INNER JOIN states AS st ON v.state=st.id 
				INNER JOIN cities AS c ON v.city=c.id 
				LEFT JOIN (
						SELECT COUNT(*) AS product_tagged_count,vendor_id FROM  vendor_productvarient_tag GROUP BY vendor_id 
				) AS vt ON v.id=vt.vendor_id AND vt.vendor_id=v.id
				WHERE v.status='0' ORDER BY v.id DESC";
		$query = $this->client_db->query($sql);
		if($query){
			return $query->result_object($query);
		}
		else{
			return (object)array();
		}
		
	}

	public function CreateVendor($data)
	{

		if($this->client_db->insert('vendor',$data))
   		{
          return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	


	public function UpdateVendor($user_data,$id)
	{
		$this->client_db->where('id',$id);

		if($this->client_db->update('vendor',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	public function DeleteVendor($user_data,$id)
	{
		$this->client_db->where('id',$id);

		if($this->client_db->update('vendor',$user_data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}
	
	public function GetVendorData($id)
	{
		$this->client_db->select('v.*,cnt.name AS country_name,st.name AS state_name,c.name AS city_name');
		$this->client_db->from('vendor as v');
		$this->client_db->where(['v.id'=>$id]);
		$this->client_db->join('countries AS cnt', 'v.country_id=cnt.id','left');
		$this->client_db->join('states AS st', 'v.state=st.id','left');
		$this->client_db->join('cities AS c', 'v.city=c.id','left');
		$result=$this->client_db->get();
		if($resul){
			return $result->row();
		}
		else{
			return (object)array();
		}
		

	}

	function validate_form_data()
    { 	
    	
        //$this->router->fetch_class();
		$method = $this->router->fetch_method();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('company_name', 'Company', 'required');
        $this->form_validation->set_rules('contact_person', 'Contact person', 'required');
        $this->form_validation->set_rules('designation', 'Designation', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');        
        // $this->form_validation->set_rules('website', 'Website', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');

        if ($this->form_validation->run() == TRUE)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    // AJAX SEARCH START
    function vendor_product_list_count($vendor_id='')
    {     
    	return 10; 
        $result = array();        
        $subsql = ' AND v_tag.vendor_id='.$vendor_id;
        
        $query = $this->client_db->query("SELECT v_tag.*,v_tag.id AS tag_auto_id,pv.*
        						  FROM vendor_productvarient_tag AS v_tag 
        						  left JOIN product_varient AS pv ON v_tag.product_varient_id=pv.id
        						  WHERE 1=1 $subsql",false);
        if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
        
    }

    function vendor_product_list($limit=0,$start=0,$vendor_id='')
    {
        
        $result = array();        
        $subsql = " AND v_tag.vendor_id='".$vendor_id."'";
        
        $query = $this->client_db->query("SELECT v_tag.*,v_tag.id AS tag_auto_id,pv.*
        						  FROM vendor_productvarient_tag AS v_tag 
        						  left JOIN product_varient AS pv ON v_tag.product_varient_id=pv.id
        						  WHERE v_tag.is_deleted='N' $subsql ORDER BY v_tag.id DESC LIMIT $start,$limit",false);
        
        // echo $last_query = $this->client_db->last_query();die();
		if($query){
			if($query->num_rows() > 0)
			{
				return $query->result_array();            
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
    // AJAX SEARCH END

    public function add_vendor_product($data)
	{
		if($this->client_db->insert('vendor_productvarient_tag',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function update_vendor_product($post_data,$id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('vendor_productvarient_tag',$post_data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}

	function delete_vendor_product($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('vendor_productvarient_tag'))
		{
			return true;
		}
		else
		{
			return false;
		}			
	}

	public function add_batch_vendor_product($data){
		if($this->client_db->insert_batch('vendor_productvarient_tag',$data)){
           return true;
   		}else{
          return false;
   		}
	}

	function get_vendor_key_val()
	{
		$result = array();           
		$subsql = '';      
		$subsql .= " AND status='0'";
		$sql="SELECT * FROM vendor WHERE is_deleted='N' $subsql ORDER BY company_name";         
		$query = $this->client_db->query($sql,false);        
		// $last_query = $this->client_db->last_query(); 
		if($query){
			foreach ($query->result() as $row) {               
				$result[$row->id] = $row->company_name;
			}
		}   
		else{

		}    
		
		return $result;        
	}

	public function GetVendorListFilter($search_data){
		$result = array();           
		$subsql = '';      
		$subsql .= " AND status='0'";
		$subsql .=" AND vendor.company_name LIKE ('%".$search_data['search_keyword']."%')";
		if(isset($search_data['existing_vendors']) && $search_data['existing_vendors']!='')
		{
			$subsql .=" OR vendor.id IN  (".$search_data['existing_vendors'].")";
		}
		$sql="SELECT * FROM vendor WHERE is_deleted='N' $subsql ORDER BY company_name";         
		$query = $this->client_db->query($sql,false);        
		// $last_query = $this->client_db->last_query();       
		if($query){
			foreach ($query->result() as $row){               
			$result[$row->id] = $row->company_name;
			}
		} 

		
		return $result; 


	}

	public function searchVendor($search='')
	{
		$sql = "SELECT v.id,
		v.email,
		v.mobile,
		v.office_phone,
		v.website,
		v.contact_person,
		v.designation,
		v.photo,
		v.visiting_card_font,
		v.visiting_card_back,
		v.company_name,
		v.address,
		v.country_id,
		v.state,
		v.city,
		v.zip,
		v.product_brochure,
		v.company_brochure,
		v.status,
		v.create_date,
		v.modify_date,
		vt.*,
		cnt.name AS country_name,
		st.name AS state_name,
		c.name AS city_name
		FROM vendor AS v 
		LEFT JOIN countries AS cnt ON v.country_id=cnt.id 
		LEFT JOIN states AS st ON v.state=st.id 
		LEFT JOIN cities AS c ON v.city=c.id";
		if($search){
			//$sql .=" INNER JOIN vendor_productvarient_tag AS p ON p.vendor_id=v.id";
			$sql .= " INNER JOIN (
			SELECT t1.vendor_id,GROUP_CONCAT(t2.name) AS p_name FROM  vendor_productvarient_tag AS t1 INNER JOIN product_varient AS t2 ON t1.product_varient_id=t2.id GROUP BY vendor_id 
		) AS vp ON vp.vendor_id=v.id";

		}
		$sql .= " LEFT JOIN (
			SELECT COUNT(t1.product_varient_id) AS product_tagged_count,t1.vendor_id FROM  vendor_productvarient_tag AS t1 INNER JOIN product_varient AS t2 ON t1.product_varient_id=t2.id AND t2.status='0' GROUP BY t1.vendor_id 
		) AS vt ON v.id=vt.vendor_id  WHERE v.is_deleted='N'";
		if($search){
			$sql .= " AND  (v.company_name LIKE '%".$search."%' || v.contact_person LIKE '%".$search."%' || v.mobile LIKE '%".$search."%' || v.email LIKE '%".$search."%' || vp.p_name LIKE '%".$search."%')";
			//$sql.=" AND v.company_name LIKE ('%".$search."%')";
		}
		$sql .=" ORDER BY v.id DESC";
		//echo $sql; die();
		$result=$this->client_db->query($sql);
		if($result){
			if($result->num_rows()){
				return $result->result_array();
			}else{
				return array();
			}
		}
		else{
			return array();
		}
		
	}

	public function getVendos($type){
		$sql = "SELECT v.*,vt.*,cnt.name AS country_name,st.name AS state_name,c.name AS city_name
		FROM vendor AS v 
		LEFT JOIN countries AS cnt ON v.country_id=cnt.id 
		LEFT JOIN states AS st ON v.state=st.id 
		LEFT JOIN cities AS c ON v.city=c.id 
		LEFT JOIN (
			SELECT COUNT(*) AS product_tagged_count,vendor_id FROM  vendor_productvarient_tag GROUP BY vendor_id 
		) AS vt ON v.id=vt.vendor_id AND vt.vendor_id=v.id
		ORDER BY v.id DESC";
		$res=0;
		$result=$this->client_db->query($sql);
		if($result){
			if($result->num_rows()){
				if($type=='count'){
					return $result->num_rows();
				}else{
					return $result->result_object();
				}
			}else{
				if($type=='count'){
					return 0;
				}else{
					return (object)array();
				}
			}
		}
		else{
			return 0;
		}
		
	}

	public function getApprovedVendos($type){
		if($type=='count'){
			return '0';
		}else{
			return array();
		}
	}

	public function getRejectedVendos($type){
		if($type=='count'){
			return '0';
		}else{
			return array();
		}
	}

	public function getPremiumVendos($type){
		if($type=='count'){
			return '0';
		}else{
			return array();
		}
	}

	public function getBlacklistedVendos($type){
		if($type=='count'){
			return '0';
		}else{
			return array();
		}
	}

	function tagged_product_count($vendor_id='')
    {            
        $subsql = ' AND vendor_id='.$vendor_id;        
        $query = $this->client_db->query("SELECT * FROM vendor_productvarient_tag WHERE is_deleted='N' $subsql",false);        
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
        
    }
}
?>