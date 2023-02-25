<?php
class Opportunity_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() {
        parent::__construct();
		// $this->load->database();
		$this->class_name=$this->router->fetch_class();
		$this->user_arr=array();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    }

    public function delete_lead_opportunity($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('lead_opportunity'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function CreateLeadOportunity($data,$client_info=array())
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
		
		if($this->client_db->insert('lead_opportunity',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function UploadDoc($lead_id,$doc_name,$quotation_id,$opportunity_id,$image_filename)
	{	
		$dt=date('Y-m-d');	
		 $sql="INSERT INTO `lead_uploadsfile`(`lead_id`, `doc_name`, `attachted_name`, `opportunity_id`, `quotation_id`, `c_date`) VALUES ('$lead_id','$doc_name','$image_filename','$opportunity_id','$quotation_id','$dt')";
		
		$result=$this->client_db->query($sql);
		//$result->result();
		if($result){
			$result->result();
		}
		else{
			(object)array();
		}
	}
	
	public function Createuploadbrochure($data)
	{
		
		if($this->client_db->insert('lead_uploadsfile',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function Uploadbrochure($data)
	{
		
		if($this->client_db->insert('lead_uploadsfile',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}

	public function CreateOportunityWisePo($data)
	{		
		if($this->client_db->insert('lead_opportunity_wise_po',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
   public function UpdateLeadOportunity($data,$opportunity_id,$client_info=array())
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

		$this->client_db->where('id',$opportunity_id);
		if($this->client_db->update('lead_opportunity',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
   

	public function GetOpportunityStageListAll($client_info=array())
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

		$this->client_db->select('id,name,class_name,sort');
		$this->client_db->from('opportunity_stage');
		$this->client_db->order_by('sort','asc');
		$this->client_db->where('is_deleted','N');
		$result=$this->client_db->get();
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}
	public function GetOpportunityStatusListAll()
	{
		$this->client_db->select('*');
		$this->client_db->from('opportunity_status');
		$this->client_db->order_by('sort','asc');
		$result=$this->client_db->get();
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}

	}
	public function GetOpportunityListAll($lead_id)
	{		
		$sql="select 
				count(qt.id) as tot_quotation, 
				lo.*,
				if(count(lowp.id),'Y','N') AS is_po_received,
				lowp.id AS lowp_id,
				lowp.file_name AS po_file_name,
				lowp.cc_to_employee AS po_cc_to_employee,
				lowp.is_send_acknowledgement_to_client AS po_is_send_acknowledgement_to_client,
				lowp.comments AS po_comments ,
				lowp.lead_opportunity_id,
				qt.id AS q_id,
				qt.is_extermal_quote,
				qt.file_name,
				qt.create_date AS quotation_sent_on,
				qs.name as stage_name,
				qs.class_name as stage_class_name,
				qs.name as status_name,
				qs.class_name as status_class_name,
				currency.name AS currency_name,
				currency.code AS currency_code,
				COUNT(op.product_id) AS product_count,
				SUM(op.price*op.unit) AS approx_deal_value
				FROM lead_opportunity as lo 
				LEFT JOIN lead_opportunity_wise_po AS lowp on lowp.lead_opportunity_id=lo.id 
				LEFT JOIN quotaion_status AS qs on qs.id=lo.status 
				LEFT JOIN quotation AS qt on qt.opportunity_id=lo.id
				LEFT JOIN currency on currency.id=lo.currency_type  
				LEFT JOIN opportunity_product AS op ON op.opportunity_id=lo.id 
				WHERE lo.lead_id='".$lead_id."' group by lo.id 
				ORDER BY lo.id DESC";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function GetSentToClientStatusOpportunityListAll($lead_id,$client_info=array())
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

		$sql="select 
				count(qt.id) as tot_quotation, 				
				lo.id,
				lo.lead_id,
				lo.source_id,
				lo.opportunity_title,
				lo.deal_value,
				lo.deal_value_as_per_purchase_order,
				lo.currency_type,
				lo.create_date,
				lo.modify_date,
				lo.status,
				lo.is_active,
				lo.is_deleted,
				if(count(lowp.id),'Y','N') AS is_po_received,
				lowp.id AS lowp_id,
				lowp.file_name AS po_file_name,
				lowp.cc_to_employee AS po_cc_to_employee,
				lowp.is_send_acknowledgement_to_client AS po_is_send_acknowledgement_to_client,
				lowp.comments AS po_comments ,
				lowp.lead_opportunity_id,
				qt.id AS q_id,
				qt.is_extermal_quote,
				qt.file_name,
				qt.create_date AS quotation_sent_on,
				qs.name as stage_name,
				qs.class_name as stage_class_name,
				qs.name as status_name,
				qs.class_name as status_class_name,
				currency.name AS currency_name,
				currency.code AS currency_code,
				COUNT(op.product_id) AS product_count,
				SUM(op.price*op.unit) AS approx_deal_value
				FROM lead_opportunity as lo 
				LEFT JOIN lead_opportunity_wise_po AS lowp on lowp.lead_opportunity_id=lo.id 
				LEFT JOIN quotaion_status AS qs on qs.id=lo.status 
				LEFT JOIN quotation AS qt on qt.opportunity_id=lo.id
				LEFT JOIN currency on currency.id=lo.currency_type  
				LEFT JOIN opportunity_product AS op ON op.opportunity_id=lo.id 
				WHERE lo.lead_id='".$lead_id."' AND lo.status='2' group by lo.id 
				ORDER BY lo.id DESC";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function GetOpportunity($id)
	{		
		$sql="select 
				count(qt.id) as tot_quotation, 
				lo.*,
				if(count(lowp.id),'Y','N') AS is_po_received,
				lowp.id AS lowp_id,
				lowp.file_name AS po_file_name,
				lowp.cc_to_employee AS po_cc_to_employee,
				lowp.is_send_acknowledgement_to_client AS po_is_send_acknowledgement_to_client,
				lowp.comments AS po_comments ,
				lowp.lead_opportunity_id,
				qt.id AS q_id,
				qt.is_extermal_quote,
				qt.file_name,
				qt.create_date AS quotation_sent_on,
				qs.name as stage_name,
				qs.class_name as stage_class_name,
				qs.name as status_name,
				qs.class_name as status_class_name,
				currency.name AS currency_name,
				currency.code AS currency_code,
				COUNT(op.product_id) AS product_count,
				SUM(op.price*op.unit) AS approx_deal_value
				FROM lead_opportunity as lo 
				LEFT JOIN lead_opportunity_wise_po AS lowp on lowp.lead_opportunity_id=lo.id 
				LEFT JOIN quotaion_status AS qs on qs.id=lo.status 
				LEFT JOIN quotation AS qt on qt.opportunity_id=lo.id
				LEFT JOIN currency on currency.id=lo.currency_type  
				LEFT JOIN opportunity_product AS op ON op.opportunity_id=lo.id 
				WHERE lo.id='".$id."' group by lo.id";
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	

	public function GetOpportunityData($id,$client_info=array())
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
		
		$this->client_db->select('lead_opportunity.id,lead_opportunity.lead_id,lead_opportunity.source_id,lead_opportunity.opportunity_title,lead_opportunity.deal_value,lead_opportunity.deal_value_as_per_purchase_order,lead_opportunity.currency_type,lead_opportunity.create_date,lead_opportunity.modify_date,lead_opportunity.status,lead_opportunity.is_active,currency.code AS currency_type_code');
		$this->client_db->where('lead_opportunity.id',$id);
		$this->client_db->from('lead_opportunity');
		$this->client_db->join('currency', 'currency.id = lead_opportunity.currency_type','inner');
		$result=$this->client_db->get();
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}

	}
	public function GetQuotationCount($opportunity_id)
	{		
		$sql="select count(qt.id) as tot_quotation FROM quotation AS qt WHERE qt.opportunity_id='".$opportunity_id."'";
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	public function get_opportunity_product($opportunity_id,$client_info=array())
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
			t1.id,
			t1.opportunity_id,
			t1.product_id,
			t1.unit,
			t1.unit_type,
			t1.quantity,
			t1.price,
			t1.currency_type,
			t1.discount,
			t1.gst,
			t1.create_date,
			t1.status,
			t2.name AS p_name,
			t2.code AS p_code,
			t3.type_name AS unit_type_name 
			FROM opportunity_product AS t1
			INNER JOIN product_varient AS t2 ON t1.product_id=t2.id
			INNER JOIN unit_type AS t3 ON t1.unit_type=t3.id
		WHERE t1.opportunity_id='".$opportunity_id."' ORDER BY id";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_shipping_terms($client_info=array())
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

		$this->client_db->select('id,name,sort');
		//$this->client_db->where('lead_opportunity.id',$id);
		$this->client_db->order_by('sort','asc');
		$this->client_db->from('shipping_charge_terms');
		$result=$this->client_db->get();
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_payment_terms($client_info=array())
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
		
		$this->client_db->select('id,name,sort');
		//$this->client_db->where('lead_opportunity.id',$id);
		$this->client_db->order_by('sort','asc');
		$this->client_db->from('payment_terms');
		$result=$this->client_db->get();
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_additional_charges($opp_id)
	{
		$sql="SELECT id,name FROM additional_charges WHERE id NOT IN (SELECT additional_charge_id FROM opportunity_additional_charges WHERE opportunity_id='".$opp_id."') ORDER BY id";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_selected_additional_charges($opp_id,$client_info=array())
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
			additional_charge_id,
			additional_charge_name,
			price,
			discount,
			is_discount_p_or_a,
			gst,
			create_date,
			sort 
			FROM opportunity_additional_charges 
			WHERE opportunity_id='".$opp_id."' ORDER BY sort";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_additional_charges_by_id($id)
	{
		$sql="SELECT id,name FROM additional_charges WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

	public function create_opportunity_additional_charges($data,$client_info=array())
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

		if($this->client_db->insert('opportunity_additional_charges',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}

	public function delete_opportunity_additional_charges($id)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->delete('opportunity_additional_charges'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function update_opportunity_additional_charges($data,$id,$client_info=array())
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
		if($this->client_db->update('opportunity_additional_charges',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	public function get_selected_additional_charges_by_id($id)
	{
		$sql="SELECT id,opportunity_id,
		additional_charge_id,
		additional_charge_name,
		price,
		discount,
		is_discount_p_or_a,
		gst,
		create_date FROM opportunity_additional_charges WHERE id='".$id."'";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
		
		
	}

	public function get_quotation_count($lead_id)
	{
		$sql="SELECT * FROM lead_opportunity WHERE lead_id=".$lead_id;
		$query=$this->client_db->query($sql);
		//return $query->num_rows();
		if($query) 
		{
			return $query->num_rows();         
        }
        else 
        {
            return 0;
        }
	}

	public function get_opportunity_details($opp_id)
	{
		$sql="SELECT t1.*,t2.*,
				t3.cc_to_employee AS po_cc_to_employee,
				t3.is_send_acknowledgement_to_client AS po_is_send_acknowledgement_to_client,
				t3.comments AS po_comments,
				t3.po_number,
				t3.po_date FROM lead_opportunity AS t1 
			LEFT JOIN quotation AS t2 ON t1.id=t2.opportunity_id
			LEFT JOIN lead_opportunity_wise_po AS t3 ON t1.id=t3.lead_opportunity_id WHERE t1.id='".$opp_id."'";
		$result=$this->client_db->query($sql);
		//return $result->row_array();
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	public function GetOpportunityDetails($id)
	{		
		$sql="select 
				count(qt.id) as tot_quotation, 
				lo.*,
				if(count(lowp.id),'Y','N') AS is_po_received,
				lowp.id AS lowp_id,
				lowp.file_name AS po_file_name,
				lowp.cc_to_employee AS po_cc_to_employee,
				lowp.is_send_acknowledgement_to_client AS po_is_send_acknowledgement_to_client,
				lowp.comments AS po_comments ,
				lowp.lead_opportunity_id,
				qt.id AS q_id,
				qt.is_extermal_quote,
				qt.file_name,
				qt.create_date AS quotation_sent_on,
				qs.name as stage_name,
				qs.class_name as stage_class_name,
				qs.name as status_name,
				qs.class_name as status_class_name,
				currency.name AS currency_name,
				currency.code AS currency_code,
				COUNT(op.product_id) AS product_count,
				SUM(op.price*op.unit) AS approx_deal_value
				FROM lead_opportunity as lo 
				LEFT JOIN lead_opportunity_wise_po AS lowp on lowp.lead_opportunity_id=lo.id 
				LEFT JOIN quotaion_status AS qs on qs.id=lo.status 
				LEFT JOIN quotation AS qt on qt.opportunity_id=lo.id
				LEFT JOIN currency on currency.id=lo.currency_type  
				LEFT JOIN opportunity_product AS op ON op.opportunity_id=lo.id 
				WHERE lo.id='".$id."' group by lo.id 
				ORDER BY lo.id DESC";
		$result=$this->client_db->query($sql);
		//return $result->row();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	public function get_quotation_id_from_leads($lead_ids)
	{
		$subsql='';		
		$sql="SELECT id
		FROM  lead_opportunity 
		WHERE lead_id IN (".$lead_ids.") AND is_deleted='N' $subsql ORDER BY id ASC LIMIT 0,1";		
		
		$query = $this->client_db->query($sql,false);
		if($query){
			if($query->num_rows()>0) 
			{
				return $query->row()->id;            
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

	public function get_all_quotation_id_from_leads($lead_ids)
	{
		$subsql='';		
		$sql="SELECT id
		FROM  lead_opportunity 
		WHERE lead_id IN (".$lead_ids.") AND is_deleted='N' $subsql ORDER BY id ASC";		
		$query = $this->client_db->query($sql,false);  
		$arr=[]; 
		if($query){
			if($query->num_rows()>0) 
			{
				foreach ($query->result() as $row) 
				{ 
					array_push($arr,$row->id);				
				}
				return $arr;            
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

	public function GetLatestOpportunity($lead_id,$client_info=array())
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
		$sql="select 
				count(qt.id) as tot_quotation, 
				lo.id,
				lo.lead_id,
				lo.source_id,
				lo.opportunity_title,
				lo.deal_value,
				lo.currency_type,
				lo.create_date,
				lo.modify_date,
				lo.status,
				if(count(lowp.id),'Y','N') AS is_po_received,
				lowp.id AS lowp_id,
				lowp.file_name AS po_file_name,
				lowp.cc_to_employee AS po_cc_to_employee,
				lowp.is_send_acknowledgement_to_client AS po_is_send_acknowledgement_to_client,
				lowp.comments AS po_comments ,
				lowp.lead_opportunity_id,
				qt.id AS q_id,
				qt.quote_no,
				qt.quote_valid_until,
				qt.is_extermal_quote,
				qt.file_name,
				qt.create_date AS quotation_sent_on,				
				qs.name as stage_name,
				qs.class_name as stage_class_name,
				qs.name as status_name,
				qs.class_name as status_class_name,
				currency.name AS currency_name,
				currency.code AS currency_code,
				COUNT(op.product_id) AS product_count,
				SUM(op.price*op.unit) AS approx_deal_value
				FROM lead_opportunity as lo 
				LEFT JOIN lead_opportunity_wise_po AS lowp on lowp.lead_opportunity_id=lo.id 
				LEFT JOIN quotaion_status AS qs on qs.id=lo.status 
				LEFT JOIN quotation AS qt on qt.opportunity_id=lo.id
				LEFT JOIN currency on currency.id=lo.currency_type  
				LEFT JOIN opportunity_product AS op ON op.opportunity_id=lo.id 
				WHERE lo.lead_id='".$lead_id."' AND lo.status='2' group by lo.id 
				ORDER BY lo.id DESC LIMIT 0,1";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
		
	}

	public function delete_opp_product_by_opp_and_pid($oppid,$pid,$client_info=array())
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

		$this->client_db->where('opportunity_id',$oppid);
		$this->client_db->where('product_id',$pid);
		if($this->client_db->delete('opportunity_product'))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function GetStageList($ids,$client_info=array())
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
		$sql="SELECT id,name,class_name,sort
				FROM opportunity_stage 
				WHERE id IN (".$ids.")  ORDER BY sort ASC";
		$result=$this->client_db->query($sql);
		//return $result->result_array();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function get_type_wise_lead_stage_arr($type)
	{
		if($type=='AL')
		{			
			$sql="SELECT id FROM opportunity_stage 
			WHERE id NOT IN ('3','4','5','6','7')";
			$result=$this->client_db->query($sql);
	    	if($result->num_rows())
	    	{
	    		$tmp_return=array();
				$rows=$result->result();
	        	foreach($rows AS $row)
	        	{
	        		array_push($tmp_return, $row->id);
	        		// $tmp_return .="'".$row->id."',";
	        	}
	        	// return rtrim($tmp_return, ',');
	        	return $tmp_return;
			}
			else
			{
				return '';
			}
		}
		else if($type=='LL')
		{
			return "'3','5','6','7'";

		}
		else if($type=='WL')
		{
			return "'4'";	
		}
		else
		{
			$sql="SELECT id FROM opportunity_stage";
			$result=$this->client_db->query($sql);
			if($result->num_rows())
	    	{
	    		$tmp_return='';
				$rows=$result->result();
	        	foreach($rows AS $row)
	        	{
	        		array_push($tmp_return, $row->id);
	        		// $tmp_return .="'".$row->id."',";
	        	}
	        	// return rtrim($tmp_return, ',');
	        	return $tmp_return;
			}
			else
			{
				return '';
			}
		}
	}

	public function UpdateOportunityWisePo($data,$id)
	{		
		$this->client_db->where('id',$id);
		if($this->client_db->update('lead_opportunity_wise_po',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function get_additional_charges_except_po($arg)
	{
		$subsql='';
		if($arg['is_pfi_or_inv'] && ($arg['po_pro_forma_invoice_id'] || $arg['po_invoice_id']))
		{
			if($arg['is_pfi_or_inv']=='pfi')
			{
				$subsql .=" AND id NOT IN (SELECT additional_charge_id FROM po_pro_forma_invoice_additional_charges WHERE po_pro_forma_invoice_id='".$arg['po_pro_forma_invoice_id']."')";
			}
			else if($arg['is_pfi_or_inv']=='inv')
			{
				$subsql .=" AND id NOT IN (SELECT additional_charge_id FROM po_invoice_additional_charges WHERE po_invoice_id='".$arg['po_invoice_id']."')";
			}
			
		}
		$sql="SELECT id,name FROM additional_charges WHERE 1=1 $subsql ORDER BY id";
		// echo $sql; die();
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function GetOpportunityListAllForQuotationPopup($lead_id,$client_info=array())
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
		$sql="select 
				lo.id,
				lo.opportunity_title,
				lo.deal_value,
				lo.create_date,
				lo.modify_date,
				lo.status,
				if(count(lowp.id),'Y','N') AS is_po_received,
				lowp.id AS lowp_id,
				lowp.file_name AS po_file_name,				
				qs.class_name as status_class_name,
				qs.name as status_name,
				count(qt.id) as tot_quotation, 
				qt.id AS q_id,
				qt.create_date AS quotation_sent_on,
				qt.is_extermal_quote,
				qt.file_name,				
				currency.code AS currency_code,
				COUNT(op.product_id) AS product_count  
				FROM lead_opportunity as lo 
				LEFT JOIN lead_opportunity_wise_po AS lowp on lowp.lead_opportunity_id=lo.id 
				LEFT JOIN quotaion_status AS qs on qs.id=lo.status 
				LEFT JOIN quotation AS qt on qt.opportunity_id=lo.id
				LEFT JOIN currency on currency.id=lo.currency_type  
				LEFT JOIN opportunity_product AS op ON op.opportunity_id=lo.id 
				WHERE lo.lead_id='".$lead_id."' group by lo.id 
				ORDER BY lo.id DESC";
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