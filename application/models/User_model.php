<?php
class User_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() 
	{
        parent::__construct();
		// $this->load->database();
		$this->user_arr=array();
		$this->class_name=$this->router->fetch_class();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    }

    // ===================================================
	// ADMIN USER

	public function chk_valid_admin_login($username,$password)
	{	
		$sql="SELECT * FROM user WHERE (id='".$username."' || username='".$username."') AND password='".$password."' AND status='0'";
		$query = $this->client_db->query($sql,false );
		if($query){
			if($query->num_rows()==0)
			{
			return FALSE;
			}
			else
			{
				$row=$query->row();
				return $row->id;
			}
		}
		else{
			return FALSE;
		}
		
	}

	public function get_admin_user_details($id)
	{	
		$sql="SELECT * FROM user WHERE id='".$id."' AND status='0'";
		$query = $this->client_db->query($sql,false );
		if($query){
			return $row=$query->row();
		}
		else{
			return (object)array();
		}
		
	}
	
	// ADMIN USER
	// ===================================================
    


	public function GetUserListAll($list_user_or_admin='',$client_info=array())
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
		
		if($list_user_or_admin!='')
		{
			$this->client_db->where('LOWER(user_type)',strtolower($list_user_or_admin));
		}
		$this->client_db->select('user.*,category.category_name dept_name');
		$this->client_db->from('user');
		$this->client_db->join('category', 'user.department_id = category.id', 'left'); 	
		$this->client_db->where('user.status','0');		
		$result=$this->client_db->get();
		//echo $this->client_db->last_query();;
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}	

	public function GetUserList($userIds='',$client_info=array())
	{
		// if($this->class_name=='rest_meeting')
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
		$sqbsql="";

		if($userIds){
			$sqbsql .=" AND user.id IN (".$userIds.")";
		}
		$sql="SELECT 
			user.id,
			user.department_id,
			user.manager_id,
			user.designation_id,
			user.functional_area_id,
			user.user_type,
			user.name,
			user.designation,
			user.email,
			user.password,
			user.mobile,
			user.company_name,
			user.address,
			user.city,
			user.state,
			user.country_id,
			user.website,
			user.company_industry_id,
			user.company_profile,
			user.date_of_birth,
			user.photo,
			user.personal_email,
			user.personal_mobile,
			user.gender,
			user.marital_status,
			user.marriage_anniversary,
			user.spouse_name,
			user.salary,
			user.salary_currency_code,
			user.pan,
			user.aadhar,
			user.joining_date,
			user.next_appraisal_date,
			user.sales_target_revenue,
			user.sales_target_revenue_type,
			user.sales_target_no_of_deal,
			user.target_setting,
			user.lms_url,
			user.forget_key,
			user.create_date,
			user.modify_date,
			user.status,
			user.user_token 
		  FROM user WHERE user.status='0' $sqbsql ORDER BY user.id ASC";
		$result=$this->client_db->query($sql);
		//return $this->client_db->last_query();;
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
		
	}	

	function GetAllUsers($status='',$client_info=array())
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
		if($status!='')
		{
			$subsql .=" AND status='".$status."'";
		}
		else
		{
			$subsql .=" AND status IN ('0','1')";
		}
		$sql="SELECT id,name FROM user WHERE 1=1 $subsql ORDER BY id ASC";
		$result=$this->client_db->query($sql);
		// echo $this->client_db->last_query();die();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
		
	}

	function CreateUser($data)
	{

		if($this->client_db->insert('user',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function Login($username,$password)
	{
		$this->client_db->from('user');
		$this->client_db->where('id',$username);
		$this->client_db->where('password',$password);
		$this->client_db->where('status','0');	
		$result=$this->client_db->get();
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_loggedin_info($id,$password,$client_info)
	{
		if($this->class_name=='rest_user')
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

		$sql="SELECT id,
				name,
				email,
				mobile,
				company_name,
				photo 
				FROM user 
				WHERE id='".$id."' 
				AND password='".$password."' 
				AND status='0'";
		$query  = $this->client_db->query($sql);
		// echo $this->client_db->last_query();die();
		if($query){
			if($query->num_rows()>0)
			{
				$row=$query->row();
				return array(
						'id'=>$row->id,
						'name'=>$row->name,
						'email'=>$row->email,
						'mobile'=>$row->mobile,
						'company_name'=>$row->company_name,
						'profile_image'=>$row->photo,
						'profile_image_path'=>assets_url().'uploads/clients/'.$client_info->id.'/admin/thumb/'
						);
			}
			else
			{
				return '';
			}
		}
		else{
			return '';
		}
		
		
	}
	

	
	
	function CreateAdmin($user_data)
	{
		if($this->client_db->insert('user',$user_data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function UpdateAdmin($user_data,$id,$client_info=array())
	{
		if($this->class_name=='rest_lead' || $this->class_name=='account')
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
		if($this->client_db->update('user',$user_data))
		{
			return true;		  
		}

		else
		{
			return false;
		}		
	}

	function DeleteAdmin($user_data,$id)
	{

		$this->client_db->where('id',$id);

		if($this->client_db->update('user',$user_data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		

	}
	
	public function GetAdminData($id,$client_info=array())
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
		
		$this->client_db->from('user');
		$this->client_db->where('id',$id);
		//$this->client_db->where('status','0');
		$this->client_db->select('id,username,employee_type_id,branch_id,department_id,manager_id,designation_id,functional_area_id,user_type,name,designation,email,password,mobile,company_name,address,city,state,country_id,website,company_industry_id,company_profile,date_of_birth,photo,personal_email,personal_mobile,gender,marital_status,marriage_anniversary,spouse_name,salary,salary_currency_code,pan,aadhar,joining_date,next_appraisal_date,sales_target_revenue,sales_target_revenue_type,sales_target_no_of_deal,target_setting,lms_url,forget_key,create_date,modify_date,status,user_token');
		$result=$this->client_db->get();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		

	}
	
	public function GetSuperAdminData()
	{
		$this->client_db->from('user');
		$this->client_db->where('user_type','Admin');
		$this->client_db->where('status','0');
		$result=$this->client_db->get();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
		
	}
	
	public function GetAdminDataById($id)
	{
		$this->client_db->from('user');
		$this->client_db->where('id',$id);
		$this->client_db->where('status','0');
		$result=$this->client_db->get();
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	public function EmailExist($email)
	{
		$this->client_db->from('user');
		$this->client_db->where('email',$email);		
		$this->client_db->where('status','0');		
		$result=$this->client_db->get();
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	public function MobileExist($mobile)
	{
		$this->client_db->from('user');
		$this->client_db->where('mobile',$mobile);		
		$this->client_db->where('status','0');		
		$result=$this->client_db->get();
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}
	
	public function KeyExist($key,$client_info=array())
	{
		if($this->class_name=='account')
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

		$this->client_db->from('user');
		$this->client_db->where('forget_key',$key);
		$this->client_db->where('status','0');
		$this->client_db->select('id,department_id,manager_id,designation_id,functional_area_id,user_type,name,designation,email,password,mobile,company_name,address,city,state,country_id,website,company_industry_id,company_profile,date_of_birth,photo,personal_email,personal_mobile,gender,marital_status,marriage_anniversary,spouse_name,salary,salary_currency_code,pan,aadhar,joining_date,next_appraisal_date,sales_target_revenue,sales_target_revenue_type,sales_target_no_of_deal,target_setting,lms_url,forget_key,create_date,modify_date,status,user_token');
		$result=$this->client_db->get();
		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}


	

    public function set_permission($client_info=array())
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
    	$user_id = $this->input->post('user_id');
		$package_id = $this->input->post('package_id');
		$chk_menu_arr = $this->input->post('chk_menu');		
		$chk_non_menu_arr = $this->input->post('chk_non_menu');
		$chk_controller_method = $this->input->post('chk_controller_method');	
		$user_wise_permission = $this->input->post('user_wise_permission');	
		
		// --------------------
		//delete
		$sql_delete = "DELETE FROM tbl_user_wise_permission WHERE user_id='".$user_id."'";
		$this->client_db->query($sql_delete);
		if(count($user_wise_permission)>0)
		{
			foreach($user_wise_permission as $k=>$v)
			{
				// insert
				$sql_insert = "INSERT INTO 
				tbl_user_wise_permission 
				(user_id,reserved_keyword) 
				VALUES ('".$user_id."','".$v."')"; 
				$this->client_db->query($sql_insert);
			}
		}
		

		
	}

	public function get_user_permission_old($user_id)
	{
		$sql2 = "SELECT * FROM tbl_user_permission WHERE user_id='".$user_id."'";
        $query2  = $this->client_db->query($sql2);
		if($query2){
			if($query2->num_rows()>0)
			{
				$non_mennu_arr = array();
				$menu_arr = array();   
				$all = array();            
				foreach( $query2->result_array() as $row2)
				{
					if($row2['is_menu']=='Y')
					{                        
						$menu_arr[] = array('menu_id'=>$row2['menu_id'],'sub_menu_ids'=>$row2['sub_menu_ids']);
					}
					else
					{
						$non_mennu_arr[] = array(
											'package_attribute_id'=>$row2['package_attribute_id'],
											'attribute_name'=>$row2['attribute_name'],
											'reserved_keyword'=>$row2['reserved_keyword'],
											'display_value'=>$row2['display_value'],
											'calculative_value'=>$row2['calculative_value'],
											'calculative_value_unit'=>$row2['calculative_value_unit']
											);
					}
					$all[] = array(
							'package_attribute_id'=>$row2['package_attribute_id'],
							'attribute_name'=>$row2['attribute_name'],
							'reserved_keyword'=>$row2['reserved_keyword'],
							'display_value'=>$row2['display_value'],
							'calculative_value'=>$row2['calculative_value'],
							'calculative_value_unit'=>$row2['calculative_value_unit'],
							'is_menu'=>$row2['is_menu'],
							'menu_id'=>$row2['menu_id'],
							'sub_menu_ids'=>$row2['sub_menu_ids']
							);
					
				}
				$final_arr = array('menu'=>$menu_arr,'non_menu'=>$non_mennu_arr,'all'=>$all);
				return $final_arr;
			}
		}
		else{
			
		}

         
	}

	public function get_user_permission_method($user_id)
	{
		$sql = "SELECT * FROM tbl_user_permission_controller_method WHERE user_id='".$user_id."'";
        $query  = $this->client_db->query($sql);                   
        
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
		
        
	}

	public function get_user_permission_method2($user_id)
	{
		$sql = "SELECT * FROM tbl_user_permission_controller_method WHERE user_id='".$user_id."'";
        $query  = $this->client_db->query($sql);
		if($query){
			if($query->num_rows()>0)
			{    
				$final_arr = array();  
				foreach( $query->result_array() as $row)
				{   
					array_push($final_arr, $row['controller'].'@'.$row['method']);              
				}            
				return $final_arr;
			}
		}
		else{
			return array();
		}         
	}


	// =========================================
	// Department (start)
	// =========================================
	public function addDepartment($data)
	{
		if($this->client_db->insert('department',$data))
		{
		   return $this->client_db->insert_id();
		}
		else
		{
		  return false;
		}
	}

	public function editDepartment($id,$data)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('department',$data))
		{
		   return true;
		}
		else
		{
		  return false;
		}
	}

	public function GetDepartment($id)
	{
		$this->client_db->from('department');
		$this->client_db->where('id',$id);
		$this->client_db->where('is_deleted','N');
		$result=$this->client_db->get();
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

    public function GetUserDepartmentListAll($list_user_or_admin='')
	{
		// if($list_user_or_admin!='')
		// {
		// 	$this->client_db->where('LOWER(user_type)',strtolower($list_user_or_admin));
		// }
		$this->client_db->from('department');	
		$this->client_db->where('is_deleted','N');		
		$result=$this->client_db->get();
		//echo $this->client_db->last_query();;
		
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	// =========================================
	// Department (End)
	// =========================================


	// =========================================
	// Designation (start)
	// =========================================
	public function addDesignation($data)
	{
		if($this->client_db->insert('designation',$data))
		{
		   return $this->client_db->insert_id();
		}
		else
		{
		  return false;
		}
	}

	public function editDesignation($id,$data)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('designation',$data))
		{
		   return true;
		}
		else
		{
		  return false;
		}
	}

	public function GetDesignation($id)
	{
		$this->client_db->from('designation');
		$this->client_db->where('id',$id);
		$this->client_db->where('is_deleted','N');
		$result=$this->client_db->get();
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

    public function GetUserDesignationListAll($list_user_or_admin='')
	{
		// if($list_user_or_admin!='')
		// {
		// 	$this->client_db->where('LOWER(user_type)',strtolower($list_user_or_admin));
		// }
		// $this->client_db->from('designation');	
		// $this->client_db->where('is_deleted','N');		
		// $result=$this->client_db->get();
		//echo $this->client_db->last_query();;
		// return $result->result();

		$sql="SELECT t1.id,t1.name,
			COUNT(emp.id) emp_count 
				FROM designation AS t1 
                    LEFT JOIN user AS emp ON t1.id=emp.designation_id 
                    AND emp.status IN ('0','1')
                    WHERE t1.is_deleted='N'  
                    GROUP BY t1.id ORDER BY t1.name ASC ";
        $query = $this->client_db->query($sql);
        
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}

	}

	function get_designation_key_val()
	{
	  $result = array();           
	  $subsql = '';      
	  $subsql .= " AND is_deleted='N'";
	  $sql="SELECT * FROM designation WHERE 1=1 $subsql ORDER BY name";         
	  $query = $this->client_db->query($sql,false);        
		//   $last_query = $this->client_db->last_query(); 
		if($query){
			foreach ($query->result() as $row) 
			{               
				$result[$row->id] = $row->name;
			}
		}       
	  
	  	return $result;        
	}

	// =========================================
	// Designation (End)
	// =========================================

	// =========================================
	// Functional Area (start)
	// =========================================
	public function addFunctionalArea($data)
	{
		if($this->client_db->insert('functional_area',$data))
		{
		   return $this->client_db->insert_id();
		}
		else
		{
		  return false;
		}
	}

	public function editFunctionalArea($id,$data)
	{
		$this->client_db->where('id',$id);
		if($this->client_db->update('functional_area',$data))
		{
		   return true;
		}
		else
		{
		  return false;
		}
	}

	public function GetFunctionalArea($id)
	{
		$this->client_db->from('functional_area');
		$this->client_db->where('id',$id);
		$this->client_db->where('is_deleted','N');
		$result=$this->client_db->get();
		
		if($result){
			return $result->row();
		}
		else{
			return (object)array();
		}
	}

    public function GetUserFunctionalAreaListAll($list_user_or_admin='')
	{
		// if($list_user_or_admin!='')
		// {
		// 	$this->client_db->where('LOWER(user_type)',strtolower($list_user_or_admin));
		// }
		//$this->client_db->from('functional_area');	
		//$this->client_db->where('is_deleted','N');		
		//$result=$this->client_db->get();
		//echo $this->client_db->last_query();;
		//return $result->result();

		$sql="SELECT t1.id,t1.name,
			COUNT(emp.id) emp_count 
				FROM functional_area AS t1 
                    LEFT JOIN user AS emp ON t1.id=emp.functional_area_id 
                    AND emp.status IN ('0','1')
                    WHERE t1.is_deleted='N'  
                    GROUP BY t1.id ORDER BY t1.name ASC ";
        $query = $this->client_db->query($sql);
       
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}

	function get_functional_area_key_val()
	{
	  $result = array();           
	  $subsql = '';      
	  $subsql .= " AND is_deleted='N'";
	  $sql="SELECT * FROM functional_area WHERE 1=1 $subsql ORDER BY name";         
	  $query = $this->client_db->query($sql,false);        
		//   $last_query = $this->client_db->last_query();  
		if($query){
			foreach ($query->result() as $row) 
			{               
				$result[$row->id] = $row->name;
			}
		}
		else{
			return (object)array();
		}
	  
	  return $result;        
	}

	// =========================================
	// Functional Area (End)
	// =========================================

	// =========================================
	// Employee (Start)
	// =========================================
	public function duplicate_personal_email_check($personal_email)
	{
		$sql = "SELECT * FROM user WHERE personal_email='".$personal_email."' AND personal_email!=''";
        $query  = $this->client_db->query($sql);                   
        
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
        
	}

	public function duplicate_personal_mobile_check($personal_mobile)
	{
		$sql = "SELECT * FROM user WHERE personal_mobile='".$personal_mobile."'";
        $query  = $this->client_db->query($sql);                   
        
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
        
	}

	function validate_form_data()
    { 	

    	if($this->uri->segment(3)!="")
        {    
            $ignore_val_arr['id'] = $this->uri->segment(4);
        }
        //$this->router->fetch_class();       

		$method = $this->router->fetch_method();
        $this->load->library('form_validation');
        
        if($this->session->userdata['admin_session_data']['user_id']!=1){
        	$this->form_validation->set_rules('manager_id', 'Manager', 'required');
        }
        
        $this->form_validation->set_rules('user_type', 'User Type', 'required');
        if(!$this->uri->segment(3))
        {
        	$this->form_validation->set_rules('department_id[]', 'Department', 'required');
        }        
        $this->form_validation->set_rules('designation_id', 'Designation', 'required');
        $this->form_validation->set_rules('functional_area_id', 'Functional Area', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');      
        // $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique_value[user.email.'.serialize($ignore_val_arr).']');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');
        //$this->form_validation->set_rules('personal_email', 'Personal email', 'required|valid_email|is_unique_value[user.personal_email.'.serialize($ignore_val_arr).']');
        //$this->form_validation->set_rules('personal_mobile', 'Personal mobile', 'required|is_unique_value[user.personal_mobile.'.serialize($ignore_val_arr).']');
        //$this->form_validation->set_rules('joining_date', 'Joining Date', 'required');
        //$this->form_validation->set_rules('date_of_birth', 'Date Of Birth', 'required');
        //$this->form_validation->set_rules('salary', 'Salary', 'required');
        //$this->form_validation->set_rules('gender', 'Gender', 'required');
        //$this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
        //$this->form_validation->set_rules('marriage_anniversary', 'Marriage Anniversary', 'required');
        //$this->form_validation->set_rules('spouse_name', 'Spouse Name', 'required');
        //$this->form_validation->set_rules('address', 'Address', 'required');
        //$this->form_validation->set_rules('country_id', 'Country', 'required');
        //$this->form_validation->set_rules('state', 'State', 'required');
        //$this->form_validation->set_rules('city', 'City', 'required');
        if($method=="add_employee")
        {
        	$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        	$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');
        }
        if ($this->form_validation->run() == TRUE)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function get_department_wise_employee_key_val($department_ids_str='',$emp_id='')
	{
	  $result = array();           
	  $subsql = '';     
	  if($department_ids_str)
	  {
	  	$subsql .= " AND status='0' AND (department_id IN (".$department_ids_str.") OR id='1')";
	  } 
	  
	  if($emp_id)
	  {
	  	$subsql .= " AND id!='".$emp_id."'";
	  }
	  $sql="SELECT 
		  id,
		  department_id,
		  manager_id,
		  designation_id,
		  functional_area_id,
		  user_type,
		  name,
		  designation,
		  email,
		  password,
		  mobile,
		  company_name,
		  address,
		  city,
		  state,
		  country_id,
		  website,
		  company_industry_id,
		  company_profile,
		  date_of_birth,
		  photo,
		  personal_email,
		  personal_mobile,
		  gender,
		  marital_status,
		  marriage_anniversary,
		  spouse_name,
		  salary,
		  salary_currency_code,
		  pan,
		  aadhar,
		  joining_date,
		  next_appraisal_date,
		  sales_target_revenue,
		  sales_target_revenue_type,
		  sales_target_no_of_deal,
		  target_setting,
		  lms_url,
		  forget_key,
		  create_date,
		  modify_date,
		  status,
		  user_token
	   FROM user WHERE 1=1 $subsql ORDER BY CASE WHEN LOWER(user_type) = 'admin' THEN 1 ELSE 2 END, name DESC";         
	  $query = $this->client_db->query($sql,false);        
	  //return $last_query = $this->client_db->last_query(); 
		if($query){
			foreach ($query->result() as $row) 
			{               
				$result[$row->id] = $row->name.' ( Emp. ID : '.$row->id.' )';
			}
		}
	  	return $result;        
	}

	function get_employee_employee_key_val($filter_data=array())
	{
	  $result = array();           
	  $subsql = '';     
	  if($filter_data['except_emp_ids'])
	  {
	  	$subsql .= " AND id NOT IN (".$filter_data['except_emp_ids'].") ";
	  } 	  
	  
	  $sql="SELECT 
		  id,
		  department_id,
		  manager_id,
		  designation_id,
		  functional_area_id,
		  user_type,
		  name,
		  designation,
		  email,
		  password,
		  mobile,
		  company_name,
		  address,
		  city,
		  state,
		  country_id,
		  website,
		  company_industry_id,
		  company_profile,
		  date_of_birth,
		  photo,
		  personal_email,
		  personal_mobile,
		  gender,
		  marital_status,
		  marriage_anniversary,
		  spouse_name,
		  salary,
		  salary_currency_code,
		  pan,
		  aadhar,
		  joining_date,
		  next_appraisal_date,
		  sales_target_revenue,
		  sales_target_revenue_type,
		  sales_target_no_of_deal,
		  target_setting,
		  lms_url,
		  forget_key,
		  create_date,
		  modify_date,
		  status,
		  user_token
	   FROM user WHERE 1=1 $subsql ORDER BY CASE WHEN LOWER(user_type) = 'admin' THEN 1 ELSE 2 END, name DESC";         
	  $query = $this->client_db->query($sql,false);        
	  //echo $last_query = $this->client_db->last_query(); die();      
	  
	  if($query){
			foreach ($query->result() as $row) 
			{               
				$result[$row->id] = $row->name.' ( Emp. ID : '.$row->id.' )';
			}
		}
	  return $result;        
	}

	 // AJAX SEARCH START
    function get_employee_list_count($arr=NULL)
    {

		$result = array();  
		$subsql = '';    
		// ---------------------------------------
		// SEARCH VALUE
		$user_ids = $arr['user_ids'];
		$search_name = $arr['search_name'];
		$search_user_or_admin = $arr['search_user_or_admin'];
		$search_status = $arr['search_status'];
		$manager_auto_id = $arr['manager_auto_id'];
		$is_show_only_active_user = $arr['is_show_only_active_user'];
		


		if($user_ids!='')
		{
		    $subsql .= " AND  t1.id IN (".$user_ids.")";
		}

		if($search_name!='')
		{
		    //$subsql .= " AND  t1.product_id = '".$search_product."'";
		    $subsql .= " AND  t1.name LIKE '%".$search_name."%'";
		}

		if($search_user_or_admin!='')
		{
		    $subsql .= " AND  LOWER(t1.user_type) ='".$search_user_or_admin."'";
		}     

		if($search_status!='')
        {
        	$subsql .= " AND  t1.status IN (".$search_status.")";
        }    

        if($manager_auto_id!='')
		{
		    $subsql .= " AND  t1.manager_id='".$manager_auto_id."'";
		}

		if($is_show_only_active_user!='')
		{
			if($is_show_only_active_user=='Y'){
				$subsql .= " AND  t1.status='0'";
			}		    
		}
		// SEARCH VALUE
		// --------------------------------------- 

		$sql="SELECT t1.id FROM user AS t1
		LEFT JOIN category AS t2 ON t1.department_id=t2.id
		WHERE 1=1 $subsql";
		$query = $this->client_db->query($sql,false);
		//echo $last_query = $this->client_db->last_query();die();
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
		
    }
    
    function get_employee_list($arr)
    {
       
        $result = array();  
        $subsql = '';    
        $order_by_str = " ORDER BY 
        CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, t1.id ASC ";
        if($arr['filter_sort_by']!='')
        {
			/*
        	if($arr['filter_sort_by']=='P_L_M')
            	$order_by_str = " ORDER BY  t1.date_modified DESC ";
            else if($arr['filter_sort_by']=='P_H_TO_L')
            	$order_by_str = " ORDER BY  t1.price DESC";
            else if($arr['filter_sort_by']=='P_L_TO_H')
            	$order_by_str = " ORDER BY  t1.price ASC";
			*/
			$filter_sort_by_arr=explode("-",$arr['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY 
        CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, $field_name ".$order_by;
        }
        // ---------------------------------------
        // SEARCH VALUE
        $user_ids = $arr['user_ids'];
        $search_name = $arr['search_name'];
        $search_user_or_admin = $arr['search_user_or_admin'];
        $search_status = $arr['search_status'];
        $manager_auto_id = $arr['manager_auto_id'];
		$is_show_only_active_user = $arr['is_show_only_active_user'];
        $limit = $arr['limit'];
        $start = $arr['start'];
        
        if($user_ids!='')
		{
		    $subsql .= " AND  t1.id IN (".$user_ids.")";
		}

        if($search_name!='')
        {
            //$subsql .= " AND  t1.product_id = '".$search_product."'";
            $subsql .= " AND  t1.name LIKE '%".$search_name."%'";
        }

        if($search_user_or_admin!='')
        {
            $subsql .= " AND  LOWER(t1.user_type) ='".$search_user_or_admin."'";
        }   

        if($search_status!='')
        {
        	$subsql .= " AND  t1.status IN (".$search_status.")";
        }     

        if($manager_auto_id!='')
		{
		    $subsql .= " AND  t1.manager_id='".$manager_auto_id."'";
		}

		if($is_show_only_active_user!='')
		{
			if($is_show_only_active_user=='Y'){
				$subsql .= " AND  t1.status='0'";
			}		    
		}

        // SEARCH VALUE
        // --------------------------------------- 

        $sql="SELECT t1.id,
			t1.username,
			t1.employee_type_id,
			t1.branch_id,
        	t1.department_id,
        	t1.manager_id,
        	t1.designation_id,
        	t1.functional_area_id,
        	t1.user_type,
        	t1.name,
        	t1.designation,
        	t1.email,
        	t1.mobile,
        	t1.company_name,
        	t1.address,
        	t1.city,
        	t1.state,
        	t1.country_id,
        	t1.website,
        	t1.company_industry_id,
        	t1.company_profile,
        	t1.date_of_birth,
        	t1.photo,
        	t1.personal_email,
        	t1.personal_mobile,
        	t1.gender,
        	t1.marital_status,
        	t1.marriage_anniversary,
        	t1.spouse_name,
        	t1.salary,
        	t1.salary_currency_code,
        	t1.pan,
        	t1.aadhar,
        	t1.joining_date,
        	t1.next_appraisal_date,
        	t1.sales_target_revenue,
        	t1.sales_target_revenue_type,
        	t1.sales_target_no_of_deal,
        	t1.target_setting,
        	t1.lms_url,
        	t1.forget_key,
        	t1.create_date,
        	t1.modify_date,
        	t1.status,
        	t1.user_token,
        	t2.category_name AS dept_name,
        	t3.name AS manager_name,
        	t4.name AS designation_name,
        	t5.name AS functional_area_name,
        	t6.name AS country_name,
        	t7.name AS state_name,
        	t8.name AS city_name,
			t9.name AS employee_type,
			t10.name AS branch,
			if(t10.company_setting_id,'Main Branch','') AS cs_branch 
			FROM user AS t1
        LEFT JOIN category AS t2 ON t1.department_id=t2.id
        LEFT JOIN user AS t3 ON t1.manager_id=t3.id
        LEFT JOIN designation AS t4 ON t1.designation_id=t4.id
        LEFT JOIN functional_area AS t5 ON t1.functional_area_id=t5.id
        LEFT JOIN countries AS t6 ON t1.country_id=t6.id
        LEFT JOIN states AS t7 ON t1.state=t7.id
        LEFT JOIN cities AS t8 ON t1.city=t8.id 
		LEFT JOIN employee_type AS t9 ON t1.employee_type_id=t9.id 
		LEFT JOIN company_branch AS t10 ON t1.branch_id=t10.id
        WHERE 1=1 $subsql $order_by_str         
        LIMIT $start,$limit";        

        $query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();die();
		if($query){
			return $query->result_array(); 
		}
		else{
			return array();
		}
                
    }

    function get_manager_id($u_id='',$client_info=array())
    {	
    	// if($this->class_name=='cronjobs' || $this->class_name=='capture')
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
		if($u_id){
			$sql="SELECT manager_id FROM user WHERE id=$u_id"; 
			$query = $this->client_db->query($sql,false);
			if($query){
				if($query->num_rows()>0){
					$row=$query->row();   
					return $row->manager_id;
				}
				else{
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
    
    function get_employee_tree_list($user_id=0,$level=0,$user_type='user')
    {
       	
        $result = array();  
        $subsql = '';
        if($level==0)
        {
        	$subsql .= " AND t1.id='".$user_id."'";
        }
        else
        {
        	$subsql .= " AND t1.manager_id='".$user_id."'";
        }		

        $sql="SELECT t1.id,
        	t1.department_id,
        	t1.manager_id,
        	t1.designation_id,
        	t1.functional_area_id,
        	t1.user_type,
        	t1.name,
        	t1.designation,
        	t1.email,
        	t1.mobile,
        	t1.company_name,
        	t1.address,
        	t1.city,
        	t1.state,
        	t1.country_id,
        	t1.website,
        	t1.company_industry_id,
        	t1.company_profile,
        	t1.date_of_birth,
        	t1.photo,
        	t1.personal_email,
        	t1.personal_mobile,
        	t1.gender,
        	t1.marital_status,
        	t1.marriage_anniversary,
        	t1.spouse_name,
        	t1.salary,
        	t1.salary_currency_code,
        	t1.pan,
        	t1.aadhar,
        	t1.joining_date,
        	t1.next_appraisal_date,
        	t1.sales_target_revenue,
        	t1.sales_target_revenue_type,
        	t1.sales_target_no_of_deal,
        	t1.target_setting,
        	t1.lms_url,
        	t1.forget_key,
        	t1.create_date,
        	t1.modify_date,
        	t1.status,
        	t1.user_token,
        	t2.category_name AS dept_name,
        	t3.name AS manager_name,
        	t3.id AS manager_emp_id,
        	t4.name AS designation_name,
        	t5.name AS functional_area_name,
        	t6.name AS country_name,
        	t7.name AS state_name,
        	t8.name AS city_name FROM user AS t1
        LEFT JOIN category AS t2 ON t1.department_id=t2.id
        LEFT JOIN user AS t3 ON t1.manager_id=t3.id
        LEFT JOIN designation AS t4 ON t1.designation_id=t4.id
        LEFT JOIN functional_area AS t5 ON t1.functional_area_id=t5.id
        LEFT JOIN countries AS t6 ON t1.country_id=t6.id
        LEFT JOIN states AS t7 ON t1.state=t7.id
        LEFT JOIN cities AS t8 ON t1.city=t8.id
        WHERE t1.status!='2' $subsql
        ORDER BY 
        CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, t1.id DESC";        

        $query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();die();
        //return $query->result_array();  
        $arr = array();
		if($query){
			if($query->num_rows())
			{	
				$i=1;
				foreach($query->result() as $row)
				{
					$text='';
					if($row->manager_id==0)
					{
						$dept='Admin';
						$edit_tooltip_text='Edit the super admin';
					}
					else
					{
						$dept='Dept. - '.$row->dept_name;
						$edit_tooltip_text='Edit the employee';
					}

					$role_tooltip_text='Manage permission of '.$row->name;

					$view_detail ='<a href="JavaScript:void(0);" class="view_employee_details" data-id="'.$row->id.'" data-managerid="'.$row->manager_emp_id.'" data-original-title="Click to view details" data-toggle="tooltip" data-placement="top"><i class="fa fa-search-plus" aria-hidden="true"></i></a>';

					$status_link='';
					//if(is_method_available('user','change_status_employee')==TRUE)
					if(strtolower($user_type)=='admin')
					{
					// ========= Status Link Start
					$curr_status=($row->status=='0')?'<i class="fa fa-unlock text-success" aria-hidden="true"></i>':'<i class="fa fa-lock text-danger" aria-hidden="true" style="color:red !important"></i>'; 
					if($row->status==0)
					{
						$status_tooltip_text="Click to change disable";
					}
					else
					{
						$status_tooltip_text="Click to change enable";
					}

					if($row->id!=$user_id)
					{
						$status_link=' / <a href="JavaScript:void(0);" class="btn-status-change" data-curstatus="'.$row->status.'" data-id="'.$row->id.'" id="status_'.$row->id.'" data-original-title="'.$status_tooltip_text.'" data-toggle="tooltip" data-placement="top">'.$curr_status.'</a>';
					}
					
					// ========= Status Link End
					}

					$edit_link='';				
					if(is_permission_available('edit_users_non_menu')){				
						$edit_link=' / <a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_employee/'.$row->id.'" class="redirect_to_href" data-original-title="'.$edit_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil" aria-hidden="true"></i><a>';
					}
					// if(strtolower($user_type)=='admin')
					// {
					// 	$edit_link=' / <a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_employee/'.$row->id.'" class="redirect_to_href" data-original-title="'.$edit_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil" aria-hidden="true"></i><a>';
					// }
					// else
					// {
					// 	if($row->id!=$user_id)
					// 	{
					// 		if(is_method_available('user','edit_employee')==TRUE){
							//$edit_link=' / <a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_employee/'.$row->id.'" class="redirect_to_href" data-original-title="'.$edit_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil" aria-hidden="true"></i><a>';
						// 	}
						// }
						// else
						// {
							//$edit_link=' / <a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_employee/'.$row->id.'" class="redirect_to_href" data-original-title="'.$edit_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil" aria-hidden="true"></i><a>';
					// 	}
					// }
					
					

					$role_set_link='';
					// if(is_method_available('user','manage_permission')==TRUE && $user_id=='1'){
					if(is_permission_available('manage_user_permission_non_menu')){
						if($row->id!='1'){
							$role_set_link=' / <a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_permission/'.$row->id.'" class="redirect_to_href" data-original-title="'.$role_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-list" aria-hidden="true"></i></a>';
						}
					}

					$delete_link='';
					if(is_method_available('user','delete_employee')==TRUE){
					//$delete_link=' / <a href="JavaScript:void(0);" onclick="confirm_delete('.$row->id.')" data-original-title="Delete the employee" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';
					}


					$text .=$row->name.' (Emp. ID - '.$row->id.', '.$dept.') [ '.$view_detail.''.$status_link.''.$edit_link.''.$role_set_link.''.$delete_link.' ]';  

					$arr[] = array(
								'text'=>$text,
								'id'=>$row->id,
								'children'=>$this->get_employee_tree_list($row->id,$i,$user_type)
								);
					$i++;
				}
			}  
		}
        return $arr;   
    }  


   
    function get_under_employee_ids($user_id=0,$level=0,$client_info=array())
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
		
        $result = array();  
        $subsql = ''; 
        $subsql .= " AND t1.manager_id='".$user_id."'";        
        $sql="SELECT t1.id FROM user AS t1 WHERE t1.status!='2' $subsql ORDER BY 
        CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, t1.id DESC"; 
        $query = $this->client_db->query($sql,false); 
        if($query){
			if($query->num_rows())
			{
				$i=1;
				foreach($query->result() as $row)
				{
					$this->get_employee_nth_level($row->id,array());
					$i++;
				}
			}
		}        
        return array_keys($this->user_arr);
    }

    function get_self_and_under_employee_ids($user_id=0,$level=0,$client_info=array())
    {
       
		// if($this->class_name=='rest_meeting' || $this->class_name=='rest_dashboard_report')
		// if(count($client_info))
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
		if($user_id){
			$result = array();  
			$subsql = '';        
			$subsql .= " AND t1.id='".$user_id."'";			    
			$sql="SELECT t1.id FROM user AS t1 WHERE t1.status!='2' $subsql ORDER BY 
			CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, t1.id DESC"; 
			$query = $this->client_db->query($sql,false);
			if($query){
				if($query->num_rows())
				{
					$i=1;        	
					foreach($query->result() as $row)
					{
						$this->get_employee_nth_level($row->id,$client_info);
						$i++;
					}
				} 
			}
			return array_keys($this->user_arr);
		}                       
        
    }

    function get_employee_nth_level($user_id=0,$client_info=array())
    {       	
		// if($this->class_name=='rest_meeting')
		// if(count($client_info))
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
        $result = array();  
        $subsql = '';        
        $subsql .= " AND t1.manager_id='".$user_id."'";        
        $sql="SELECT t1.id FROM user AS t1 WHERE t1.status!='2' $subsql ORDER BY t1.id DESC"; 
        $query = $this->client_db->query($sql,false); 
        $tmp_arr = array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result() as $row)
				{
					$this->user_arr[$row->id] = $this->get_employee_nth_level($row->id,$client_info);
				}
			} 
		}
         
    }


    function get_employee_details($id,$client_info=array())
    {   
    	// if($this->class_name=='cronjobs' || $this->class_name=='capture')
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
        $sql="SELECT t1.id,
			t1.employee_type_id,
			t1.branch_id,
        	t1.department_id,
        	t1.manager_id,
        	t1.designation_id,
        	t1.functional_area_id,
        	t1.user_type,
        	t1.name,
        	t1.designation,
        	t1.email,
        	t1.mobile,
        	t1.company_name,
        	t1.address,
        	t1.city,
        	t1.state,
        	t1.country_id,
        	t1.website,
        	t1.company_industry_id,
        	t1.company_profile,
        	t1.date_of_birth,
        	t1.photo,
        	t1.personal_email,
        	t1.personal_mobile,
        	t1.gender,
        	t1.marital_status,
        	t1.marriage_anniversary,
        	t1.spouse_name,
        	t1.salary,
        	t1.salary_currency_code,
        	t1.pan,
        	t1.aadhar,
        	t1.joining_date,
        	t1.next_appraisal_date,
        	t1.sales_target_revenue,
        	t1.sales_target_revenue_type,
        	t1.sales_target_no_of_deal,
        	t1.target_setting,
        	t1.lms_url,
        	t1.create_date,
        	t1.modify_date,
        	t1.status,
        	t2.category_name AS dept_name,
        	t3.name AS manager_name,
        	t4.name AS designation_name,
        	t5.name AS functional_area_name,
        	t6.name AS country_name,
        	t7.name AS state_name,
        	t8.name AS city_name,
			t9.name AS employee_type,
			t10.name AS branch_name,
			if(t10.company_setting_id,'Main Branch','') AS cs_branch_name  FROM user AS t1
        LEFT JOIN category AS t2 ON t1.department_id=t2.id
        LEFT JOIN user AS t3 ON t1.manager_id=t3.id
        LEFT JOIN designation AS t4 ON t1.designation_id=t4.id
        LEFT JOIN functional_area AS t5 ON t1.functional_area_id=t5.id
        LEFT JOIN countries AS t6 ON t1.country_id=t6.id
        LEFT JOIN states AS t7 ON t1.state=t7.id
        LEFT JOIN cities AS t8 ON t1.city=t8.id 
		LEFT JOIN employee_type AS t9 ON t1.employee_type_id=t9.id
		LEFT JOIN company_branch AS t10 ON t1.branch_id=t10.id
        WHERE t1.id='".$id."'";        

        $query = $this->client_db->query($sql,false);        
        //return $last_query = $this->client_db->last_query();
		if($query){
			return $query->result_array()[0];         
		}
		else{
			return array();
		}
        
    }
    // AJAX SEARCH END
	// =========================================
	// Employee (End)
	// =========================================
	
	function get_employee_team_list($user_id=0,$level=0,$user_type='user')
    {
       	
        $result = array();  
        $subsql = '';
        if($level==0)
        {
        	$subsql .= " AND t1.id='".$user_id."'";
        }
        else
        {
        	$subsql .= " AND t1.manager_id='".$user_id."'";
        }
        $sql="SELECT t1.*,
        	t2.category_name AS dept_name,
        	t3.name AS manager_name,
        	t3.id AS manager_emp_id,
        	t4.name AS designation_name,
        	t5.name AS functional_area_name,
        	t6.name AS country_name,
        	t7.name AS state_name,
        	t8.name AS city_name FROM user AS t1
        LEFT JOIN category AS t2 ON t1.department_id=t2.id
        LEFT JOIN user AS t3 ON t1.manager_id=t3.id
        LEFT JOIN designation AS t4 ON t1.designation_id=t4.id
        LEFT JOIN functional_area AS t5 ON t1.functional_area_id=t5.id
        LEFT JOIN countries AS t6 ON t1.country_id=t6.id
        LEFT JOIN states AS t7 ON t1.state=t7.id
        LEFT JOIN cities AS t8 ON t1.city=t8.id
        WHERE t1.status!='2' $subsql
        ORDER BY 
        CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, t1.id DESC";        

        $query = $this->client_db->query($sql,false);        
        //return $last_query = $this->client_db->last_query();
        //return $query->result_array();  
        $arr = array();
		if($query){
			if($query->num_rows())
			{	
				$i=1;
				foreach($query->result() as $row)
				{
					$text=''; 
					$text .=$row->name;
					$arr[] = array(
								'text'=>$text,
								'id'=>$row->id,
								'children'=>$this->get_employee_team_list($row->id,$i,$user_type)
								);
					$i++;
				}
			} 
		}
         
        return $arr;   
    } 
	
	function get_user_key_val($user_ids='')
	{
	  $result = array();           
	  $subsql = '';      
	  $subsql .= " AND status='0'";
	  if($user_ids)
	  {
		  $subsql .= " AND id IN (".$user_ids.")";
	  }
	  $sql="SELECT * FROM user WHERE 1=1 $subsql";         
	  $query = $this->client_db->query($sql,false);        
		//   $last_query = $this->client_db->last_query();  
		if($query){
			foreach ($query->result() as $row) 
			{               
				$result[$row->id] = $row->name;
			}
		}      
	  
	  return $result;        
	}

	public function designation_wise_user_count($id='')
	{		
		if($id){
			$subsql = '';        
			$subsql .= " AND t1.designation_id='".$id."'";        
			$sql="SELECT t1.id FROM user AS t1 WHERE t1.status!='2' $subsql";
			$query = $this->client_db->query($sql,false);
			if($query){
				return $query->num_rows();
			} 
			else{
				return 0;
			}
		}
		else{
			return 0;
		}            
       
	}

	public function functional_area_wise_user_count($id='')
	{		
		if($id){
			$subsql = '';        
			$subsql .= " AND t1.functional_area_id='".$id."'";        
			$sql="SELECT t1.id FROM user AS t1 WHERE t1.status!='2' $subsql";
			$query = $this->client_db->query($sql,false);		
			if($query){
				return $query->num_rows();
			} 
			else{
				return 0;
			} 
		}
		else{
			return 0;
		}
        
	}

	function get_user_details($id,$client_info=array())
    {   

    	if($this->class_name=='account' || $this->class_name=='rest_geo_location')
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
        $sql="SELECT t1.id,
        	t1.department_id,
        	t1.manager_id,
        	t1.designation_id,
        	t1.functional_area_id,
        	t1.user_type,
        	t1.name,
        	t1.designation,
        	t1.email,
        	t1.mobile,
        	t1.company_name,
        	t1.address,
        	t1.city,
        	t1.state,
        	t1.country_id,
        	t1.website,
        	t1.company_industry_id,
        	t1.company_profile,
        	t1.date_of_birth,
        	t1.photo,
        	t1.personal_email,
        	t1.personal_mobile,
        	t1.gender,
        	t1.marital_status,
        	t1.marriage_anniversary,
        	t1.spouse_name,
        	t1.salary,
        	t1.salary_currency_code,
        	t1.pan,
        	t1.aadhar,
        	t1.joining_date,
        	t1.next_appraisal_date,
        	t1.sales_target_revenue,
        	t1.sales_target_revenue_type,
        	t1.sales_target_no_of_deal,
        	t1.target_setting,
        	t1.lms_url,
        	t1.create_date,
        	t1.modify_date,
        	t1.status FROM user AS t1 WHERE t1.id='".$id."'";       
        $query = $this->client_db->query($sql,false);        
        // return $last_query = $this->client_db->last_query();
		if($query){
			return $query->row_array();        
		}
		else{
			return array();
		}
         
    }




    // ===============================================
	// PACKAGE CHECKING
	
	function get_menu($data = array())
	{
		$result     = array();
		$condStr    = '';
		$condSubStr= '';

		if($data['available_menu_id_str']!='')
		{
		$condStr .=" AND id IN (".$data['available_menu_id_str'].")";
		}

		if($data['available_sub_menu_id_str']!='')
		{
		$condSubStr .=" AND id IN (".$data['available_sub_menu_id_str'].")";
		}

		// PARENT DB
		$tbl_menu = $this->fsas_db->dbprefix('tbl_menu');
		$tbl_sub_menu = $this->fsas_db->dbprefix('tbl_sub_menu');
		$tbl_sub_menu_wise_method = $this->fsas_db->dbprefix('tbl_sub_menu_wise_method_list');
		$sql = 'SELECT id,
		  icon,
		  menu_name,
		  is_sub_menu_available,
		  is_display_on_top,
		  sort_order,
		  is_active 
		  FROM '.$tbl_menu.'  WHERE 1 = 1 '. $condStr .' AND is_active="Y" ORDER BY sort_order ASC'; 
		$query  = $this->fsas_db->query($sql);  
		if($query){
			if ( $query->num_rows() > 0 ) 
			{
				$tempArr = array();         
				foreach( $query->result_array() as $row )
				{
				$tempSubArr = array(); 
				$sql2 = 'SELECT id,
				menu_id,
				sub_menu_name,
				link_name,
				is_available_on_top_menu,
				method_list,sort_order,
				is_display_on_user_permission,
				is_active 
				FROM '.$tbl_sub_menu.'  WHERE menu_id='.$row["id"].' '. $condSubStr .' AND is_active="Y" ORDER BY sort_order ASC';
				$query2  = $this->fsas_db->query($sql2);
				if($query2){
					if ( $query2->num_rows() > 0 ) 
					{ 
						foreach( $query2->result_array() as $row2 )
						{
							
							$tempSubMethodArr = array(); 
							$sql3 = 'SELECT id,
							menu_id,
							sub_menu_id,
							method_display_name,
							controller_name,
							method_name,
							is_display 
							FROM '.$tbl_sub_menu_wise_method.'  WHERE menu_id='.$row["id"].' AND sub_menu_id='.$row2["id"].' ORDER BY id ASC'; 
							$query3  = $this->fsas_db->query($sql3);
							if($query3->num_rows() > 0 ) 
							{ 
								foreach( $query3->result_array() as $row3 )
								{
									$tempSubMethodArr[$row3["id"]] = array(
										"method_auto_id"=>$row3["id"], 
										"method_display_name"=>$row3["method_display_name"], 
										"controller_name"=>$row3["controller_name"],
										"method_name"=>$row3["method_name"],
										"is_display"=>$row3["is_display"]
										);
								}
							}

							$tempSubArr[$row2["id"]] = array("menu_id"      => $row2["menu_id"], 
								"sub_menu_id" => $row2["id"],
								"sub_menu_name" => $row2["sub_menu_name"],
								"link_name"     => $row2["link_name"],
								"is_available_on_top_menu"     => $row2["is_available_on_top_menu"],
								"method_list"   => $row2["method_list"],
								"sort_order"    => $row2["sort_order"],
								"access_permission"    => '',
								"is_display_on_user_permission"    => $row2["is_display_on_user_permission"],
								"sub_menu_wise_method"   => $tempSubMethodArr
								);
						}
					} 
				}				         
					
				$tempArr[$row["id"]]["menu"] = array(
						"id"         => $row["id"],
						"icon"       => $row["icon"], 
						"menu_name"  => $row["menu_name"], 
						"is_sub_menu_available"  => $row["is_sub_menu_available"], 
						"is_display_on_top"  => $row["is_display_on_top"],
						"sort_order" => $row["sort_order"],
						"sub_menu"   => $tempSubArr
						);

				$result = $tempArr;     
				}
			}     
		}	

		
		return $result;
	}

	function get_package($data = array())
	{
		$sql = "SELECT id,
		  package_name,
		  package_price,
		  purchased_datetime,
		  expire_date 
		  FROM tbl_package_order WHERE '".date("Y-m-d")."' BETWEEN DATE(purchased_datetime) AND DATE(expire_date) LIMIT 1";
		$query  = $this->client_db->query($sql);
		if($query){
			if($query->num_rows()>0)
			{
				$row = $query->row_array();	     
				$sql2 = "SELECT id,
					package_order_id,
					package_id, 
					package_attribute_id,
					attribute_name,
					reserved_keyword, 
					display_value,
					calculative_value,
					calculative_value_unit,
					is_menu,
					menu_id,
					sub_menu_ids
					FROM tbl_package_order_detail WHERE package_order_id='".$row['id']."'";
				$query2  = $this->client_db->query($sql2);
				if($query2){
					if($query2->num_rows()>0)
					{
						$non_mennu_arr = array();
						$menu_arr = array();   
						$all = array();            
						foreach( $query2->result_array() as $row2)
						{
							if($row2['is_menu']=='Y')
							{                        
								$menu_arr[] = array('menu_id'=>$row2['menu_id'],'sub_menu_ids'=>$row2['sub_menu_ids']);
							}
							else
							{
								$non_mennu_arr[] = array(
											'id'=>$row2['id'],
											'package_order_id'=>$row2['package_order_id'],
											'package_id'=>$row2['package_id'],
											'package_attribute_id'=>$row2['package_attribute_id'],
											'attribute_name'=>$row2['attribute_name'],
											'reserved_keyword'=>$row2['reserved_keyword'],
											'display_value'=>$row2['display_value'],
											'calculative_value'=>$row2['calculative_value'],
											'calculative_value_unit'=>$row2['calculative_value_unit']
											);
							}
							$all[] = array(
									'id'=>$row2['id'],
									'package_order_id'=>$row2['package_order_id'],
									'package_id'=>$row2['package_id'],
									'package_attribute_id'=>$row2['package_attribute_id'],
									'attribute_name'=>$row2['attribute_name'],
									'reserved_keyword'=>$row2['reserved_keyword'],
									'display_value'=>$row2['display_value'],
									'calculative_value'=>$row2['calculative_value'],
									'calculative_value_unit'=>$row2['calculative_value_unit'],
									'is_menu'=>$row2['is_menu'],
									'menu_id'=>$row2['menu_id'],
									'sub_menu_ids'=>$row2['sub_menu_ids']
									);
							
						}
						$final_arr = array('menu'=>$menu_arr,'non_menu'=>$non_mennu_arr,'all'=>$all);
						return $final_arr;
					}
					else
					{
						return '';
					}
				}
				else{
					return '';
				}
				
			}
			else
			{
				return'package_expire';
			}
		}
		else{
			return '';
		}

		
	}

	function get_user_permission($user_id)
	{
	 
		$sql2 = "SELECT id,
		user_id,
		package_attribute_id,
		attribute_name,
		reserved_keyword,
		display_value,
		calculative_value,
		calculative_value_unit,
		is_menu,
		menu_id,
		sub_menu_ids 
		FROM tbl_user_permission WHERE user_id='".$user_id."'";
		$query2  = $this->client_db->query($sql2);
		if($query2){
			if($query2->num_rows()>0)
			{
				$non_mennu_arr = array();
				$menu_arr = array();   
				$all = array();            
				foreach( $query2->result_array() as $row2)
				{
					if($row2['is_menu']=='Y')
					{                        
						$menu_arr[] = array('menu_id'=>$row2['menu_id'],'sub_menu_ids'=>$row2['sub_menu_ids']);
					}
					else
					{
						$non_mennu_arr[] = array(
											'package_attribute_id'=>$row2['package_attribute_id'],
											'attribute_name'=>$row2['attribute_name'],
											'reserved_keyword'=>$row2['reserved_keyword'],
											'display_value'=>$row2['display_value'],
											'calculative_value'=>$row2['calculative_value'],
											'calculative_value_unit'=>$row2['calculative_value_unit']
											);
					}
					$all[] = array(
							'package_attribute_id'=>$row2['package_attribute_id'],
							'attribute_name'=>$row2['attribute_name'],
							'reserved_keyword'=>$row2['reserved_keyword'],
							'display_value'=>$row2['display_value'],
							'calculative_value'=>$row2['calculative_value'],
							'calculative_value_unit'=>$row2['calculative_value_unit'],
							'is_menu'=>$row2['is_menu'],
							'menu_id'=>$row2['menu_id'],
							'sub_menu_ids'=>$row2['sub_menu_ids']
							);
				
				}
				$final_arr = array('menu'=>$menu_arr,'non_menu'=>$non_mennu_arr,'all'=>$all);
				return $final_arr;
			}
		}
		
	}
	// PACKAGE CHECKING
	// ==============================================


	// ===============================
	// MASTER ADMIN
	function get_client_wise_user_list_rows($client_info=array())
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
		
        $sql="SELECT t1.id,
        	t1.department_id,
        	t1.manager_id,
        	t1.designation_id,
        	t1.functional_area_id,
        	t1.user_type,
        	t1.name,
        	t1.designation,
        	t1.email,
        	t1.mobile,
        	t1.company_name,
        	t1.address,
        	t1.city,
        	t1.state,
        	t1.country_id,
        	t1.website,
        	t1.company_industry_id,
        	t1.company_profile,
        	t1.date_of_birth,
        	t1.photo,
        	t1.personal_email,
        	t1.personal_mobile,
        	t1.gender,
        	t1.marital_status,
        	t1.marriage_anniversary,
        	t1.spouse_name,
        	t1.salary,
        	t1.salary_currency_code,
        	t1.pan,
        	t1.aadhar,
        	t1.joining_date,
        	t1.next_appraisal_date,
        	t1.sales_target_revenue,
        	t1.sales_target_revenue_type,
        	t1.sales_target_no_of_deal,
        	t1.target_setting,
        	t1.lms_url,
        	t1.create_date,
        	t1.modify_date,
        	t1.status,
			t1.last_login_datetime,
        	last_loggedin.*
        	FROM user AS t1         	
        	LEFT JOIN (
        		SELECT 
        		MAX(action_at) AS last_loggedin_at,
        		user_id AS ulh_last_user_id
        		FROM user_login_history 
        		WHERE action_type='LI' 
        		GROUP BY user_id 
        		ORDER BY id DESC
        	) AS last_loggedin ON t1.id=last_loggedin.ulh_last_user_id
        	WHERE 1=1 ORDER BY t1.id DESC";       
        $query = $this->client_db->query($sql,false);
        // echo $sql;die();      
        // return $last_query = $this->client_db->last_query();
		if($query){
			return $query->result_array();         
		}
		else{
			return array();
		}
        
    }
    // MASTER ADMIN
    // ===============================
	
	function add_user_login_history($data)
	{
		if($this->client_db->insert('user_login_history',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}	

	function last_user_login_history($user_id)
    {
        $sql="SELECT id,
        user_id,
        action_at,
        DATE(action_at) AS action_at_date_format,
		action_type 
        FROM user_login_history 
        WHERE user_id='".$user_id."'
        ORDER BY id DESC 
        LIMIT 0,1";       
        $query = $this->client_db->query($sql,false);
		if($query){
			return $query->row_array(); 
		}
		else{
			return array();
		}
                
    }

	public function sms_user_row($id,$client_info=array())
	{
		if($this->class_name=='cronjobs' || $this->class_name=='capture')
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
				t1.id AS u_user_id,
				t1.name AS u_user_name,
				t1.email AS u_official_email,
				t1.mobile AS u_official_mobile,
				t1.salary AS u_salary,
				t1.salary_currency_code AS u_salary_currency_code, 
				t2.name AS u_designation,
				tbl_manager.name  AS u_user_manager_name				
				FROM user AS t1				
				LEFT JOIN designation AS t2 ON t1.designation_id=t2.id 
				LEFT JOIN user AS tbl_manager ON tbl_manager.id=t1.manager_id 				
				WHERE t1.id='".$id."'";
		$result=$this->client_db->query($sql);
		// echo $last_query = $this->client_db->last_query();die();
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
		
	}

	function addGeoLocation($data,$client_info=array())
	{
		if($this->class_name=='rest_geo_location')
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
		if($this->client_db->insert('tbl_user_wise_geo_location_track',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}	

	function get_user_tree_list($user_id=0,$level=0,$user_type='user')
    {
       	
        $result = array();  
        $subsql = '';
        if($level==0)
        {
        	$subsql .= " AND t1.id='".$user_id."'";
        }
        else
        {
        	$subsql .= " AND t1.manager_id='".$user_id."'";
        }
        $sql="SELECT t1.id,t1.name 
		FROM user AS t1 
        WHERE t1.status!='2' $subsql
        ORDER BY 
        CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, t1.id DESC";        

        $query = $this->client_db->query($sql,false);        
        // return $last_query = $this->client_db->last_query();die();
        //return $query->result_array();  
        $arr = array();
		if($query){
			if($query->num_rows())
			{	
				$i=1;
				foreach($query->result() as $row)
				{
					$text='';	        	
					$text .=$row->name.'';  
					$arr[] = array(
								'text'=>$text,
								'id'=>$row->id,
								'a_attr'=>array('data-name'=>$row->name),
								'state'=>array('selected'=>true),
								'children'=>$this->get_user_tree_list($row->id,$i,$user_type)
								);
					$i++;
				}
			} 
		}         
        return $arr;   
    }

	function get_service_wise_menu()
	{
		$return=array();		
		$sql = 'SELECT 
				t1.id,
				t1.menu_keyword,
				t1.service_id,
				t1.menu_name,
				t1.icon,
				t1.sort_order,
				t1.is_show_in_left_panel,
				t1.is_active,
				t2.name AS service_name
		  FROM tbl_service_wise_menu AS t1 
		  INNER JOIN tbl_service AS t2 ON t1.service_id=t2.id  
		  WHERE t1.is_active="Y" ORDER BY t1.service_id ASC,t1.sort_order ASC'; 
		$query  = $this->fsas_db->query($sql);      
		if($query){
			if($query->num_rows()>0) 
			{
				foreach($query->result_array() as $row)
				{
					$sql2 = 'SELECT 
					t1.id,
					t1.service_wise_menu_id,
					t1.display_name,
					t1.reserve_keyword,
					t1.sort_order
					FROM tbl_service_wise_menu_wise_permission AS t1
					WHERE t1.service_wise_menu_id="'.$row['id'].'" ORDER BY t1.sort_order ASC'; 
					$query2=$this->fsas_db->query($sql2);
					$result2=$query2->result_array();
					$return[]=array('menu_list'=>$row,'menu_wise_permission_list'=>$result2);
				}
			}
		}  
			
		return $return ;	
	}

	function get_user_wise_permission($user_id,$client_info=array())
	{

		// if(is_array($client_info) && count($client_info))
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
		$reserved_keyword=array();
		$sql = "SELECT id,
		user_id,
		reserved_keyword
		FROM tbl_user_wise_permission WHERE user_id='".$user_id."'";
		$query  = $this->client_db->query($sql);
		if($query){
			if($query->num_rows()>0)
			{           
				foreach( $query->result_array() as $row)
				{
					array_push($reserved_keyword,$row['reserved_keyword']);			
				}			
			}
		}
		
		return $reserved_keyword;
	}

	public function user_tagged_service_order($user_id)
	{	
		$tagged_service_order_detail_id_arr=array();
		$sql="SELECT service_order_detail_id FROM user_wise_service_order WHERE user_id='".$user_id."'";
		$query = $this->client_db->query($sql,false );
		if($query){
			if($query->num_rows())
			{
				foreach( $query->result_array() as $row)
				{
					array_push($tagged_service_order_detail_id_arr,$row['service_order_detail_id']);			
				}	
			}
		}
		
		return $tagged_service_order_detail_id_arr;
	}

	public function all_user_wise_service_order($client_info=array())
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
		$tagged_service_order_detail_id_arr=array();
		$sql="SELECT t1.id,t1.user_id,t1.service_order_detail_id,t2.name FROM user_wise_service_order AS t1 
		INNER JOIN user AS t2 ON t1.user_id=t2.id ORDER BY id ";
		$query = $this->client_db->query($sql,false );
		
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	function GetAllUsersNotInTaggedService($status='',$s_id='',$client_info=array())
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
		if($status!='')
		{
			$subsql .=" AND status='".$status."'";
		}
		else
		{
			$subsql .=" AND status IN ('0','1')";
		}
		$sql="SELECT id,name FROM user WHERE id NOT IN (SELECT user_id FROM user_wise_service_order WHERE service_id='".$s_id."') $subsql ORDER BY id ASC";
		$result=$this->client_db->query($sql);
		// echo $this->client_db->last_query();die();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
		
	}

	public function delete_existing_tagged_service($user_id,$sod_id_from,$client_info=array())
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

		$this->client_db->where('user_id', $user_id);
		$this->client_db->where('service_order_detail_id', $sod_id_from);
    	$this->client_db->delete('user_wise_service_order');
	}
	function CreateTagService($data,$client_info=array())
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
		if($this->client_db->insert('user_wise_service_order',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}

	function existing_no_of_user_tagged_service($sod_id_to,$client_info=array())
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
		$sql="SELECT id FROM user_wise_service_order WHERE service_order_detail_id='".$sod_id_to."'";
		$query=$this->client_db->query($sql);
		
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
	}   


	// =======================================================================
	// super admin
	// =======================================================================
	
	function get_client_wise_user_list_count($arr=NULL)
    {

		if(is_array($arr['client_info']) && count($arr['client_info']))
		{
			$client_info=$arr['client_info'];
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

		$result = array();  
		$subsql = '';    
		// ---------------------------------------
		// SEARCH VALUE		
		$limit = $arr['limit'];
		$start = $arr['start'];
		// SEARCH VALUE
		// --------------------------------------- 
		$sql="SELECT t1.id FROM user AS t1
		LEFT JOIN category AS t2 ON t1.department_id=t2.id
		WHERE 1=1 $subsql";
		$query = $this->client_db->query($sql,false);
		//echo $last_query = $this->client_db->last_query();die();
		
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
    }
    
    function get_client_wise_user_list($arr)
    {	
		// if(is_array($arr['client_info']) && count($arr['client_info']))
		if(isset($arr['client_info']->db_name))
		{
			$client_info=$arr['client_info'];
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
        $result = array();  
        $subsql = '';    
        $order_by_str = " ORDER BY CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, t1.id ASC ";
        if($arr['filter_sort_by']!='')
        {
			
			$filter_sort_by_arr=explode("-",$arr['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY CASE WHEN LOWER(t1.user_type) = 'admin' THEN 1 ELSE 2 END, $field_name ".$order_by;
        }
        // ---------------------------------------
        // SEARCH VALUE        
        $limit = $arr['limit'];
        $start = $arr['start'];
        // SEARCH VALUE
        // --------------------------------------- 

        $sql="SELECT t1.id,
        	t1.department_id,
        	t1.manager_id,
        	t1.designation_id,
        	t1.functional_area_id,
        	t1.user_type,
        	t1.name,
        	t1.designation,
        	t1.email,
        	t1.mobile,
        	t1.company_name,
        	t1.address,
        	t1.city,
        	t1.state,
        	t1.country_id,
        	t1.website,
        	t1.company_industry_id,
        	t1.company_profile,
        	t1.date_of_birth,
        	t1.photo,
        	t1.personal_email,
        	t1.personal_mobile,
        	t1.gender,
        	t1.marital_status,
        	t1.marriage_anniversary,
        	t1.spouse_name,
        	t1.salary,
        	t1.salary_currency_code,
        	t1.pan,
        	t1.aadhar,
        	t1.joining_date,
        	t1.next_appraisal_date,
        	t1.sales_target_revenue,
        	t1.sales_target_revenue_type,
        	t1.sales_target_no_of_deal,
        	t1.target_setting,
        	t1.lms_url,
        	t1.forget_key,
        	t1.create_date,
        	t1.modify_date,
        	t1.status,
        	t1.user_token,
        	t2.category_name AS dept_name,
        	t3.name AS manager_name,
        	t4.name AS designation_name,
        	t5.name AS functional_area_name,
        	t6.name AS country_name,
        	t7.name AS state_name,
        	t8.name AS city_name FROM user AS t1
        LEFT JOIN category AS t2 ON t1.department_id=t2.id
        LEFT JOIN user AS t3 ON t1.manager_id=t3.id
        LEFT JOIN designation AS t4 ON t1.designation_id=t4.id
        LEFT JOIN functional_area AS t5 ON t1.functional_area_id=t5.id
        LEFT JOIN countries AS t6 ON t1.country_id=t6.id
        LEFT JOIN states AS t7 ON t1.state=t7.id
        LEFT JOIN cities AS t8 ON t1.city=t8.id
        WHERE 1=1 $subsql $order_by_str         
        LIMIT $start,$limit";        

        $query = $this->client_db->query($sql,false); 
        
		if($query){
			return $query->result_array();   
		}   
		else{
			return array();
		}   
    }
	// =======================================================================
	// super admin
	// =======================================================================
	
	function GetEmployeeType()
	{
		$sql="SELECT id,name FROM employee_type WHERE is_active='Y' AND is_deleted='N' ORDER BY sort_order ASC";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function GetBranchList()
	{		
		$sql="select 
		t1.id,
		t1.company_setting_id,
		t1.name,
		t1.contact_person,
		t1.email,
		t1.mobile,
		t1.address,
		t1.country_id,
		t1.state_id,
		t1.city_id,
		t1.pin,
		t1.gst,
		t1.created_at,
		t1.updated_at,
		t1.is_active,
		if(t1.company_setting_id,'Main Branch','') AS cs_name,
		(select contact_person from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_contact_person,
		(select email1 from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_email,
		(select mobile1 from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_mobile,
		(select address from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_address,
		(select country_id from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_country_id,
		(select state_id from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_state_id,
		(select city_id from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_city_id,
		(select pin from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_pin,
		(select gst from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_gst
		from company_branch  t1 WHERE t1.is_active='Y' AND t1.is_deleted='N'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	function GetBranchRow($id)
	{		
		$sql="select 
		t1.id,
		t1.company_setting_id,
		t1.name,
		t1.contact_person,
		t1.email,
		t1.mobile,
		t1.address,
		t1.country_id,
		t1.state_id,
		t1.city_id,
		t1.pin,
		t1.gst,
		t1.created_at,
		t1.updated_at,
		t1.is_active,
		if(t1.company_setting_id,'Main Branch','') AS cs_name,
		(select contact_person from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_contact_person,
		(select email1 from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_email,
		(select mobile1 from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_mobile,
		(select address from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_address,
		(select country_id from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_country_id,
		(select state_id from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_state_id,
		(select city_id from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_city_id,
		(select pin from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_pin,
		(select gst from company_setting t2 WHERE t1.company_setting_id=t2.id) as cs_gst
		from company_branch  t1 WHERE t1.id='".$id."'";
		$result=$this->client_db->query($sql);
		
		if($result){
			return $result->row_array();
		}
		else{
			return array();
		}
	}

	function duplicate_username_chk($id,$username)
	{
		$sql="SELECT id FROM user WHERE id!='".$id."' AND username='".$username."' AND username!=''";
		$query=$this->client_db->query($sql);
		
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
	}

	function get_employee_details_dislike($id,$client_info=array())
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
			t1.name,
			t1.mobile,
			t1.email  
		FROM user AS t1 
		WHERE t1.id='".$id."'"; 
        $query = $this->client_db->query($sql,false);
		if($query){
			return $query->result_array()[0];         
		}
		else{
			return array();
		}
    }

	public function GetUserListByIds($userIds='',$client_info=array())
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

		if($userIds){
			$subsql="";
			$subsql .=" AND user.id IN (".$userIds.")";
			$sql="SELECT user.id,user.name FROM user WHERE user.status='0' $subsql ORDER BY FIELD(ID,$userIds)";
			$result=$this->client_db->query($sql);			
			if($result){
				return $result->result_array();
			}
			else{
				return array();
			}
		}
		else{
			return array();
		}
	}	

	function GetAllUsersIdsThoseTaggedInService($s_id='',$client_info=array())
	{	
		if($s_id){
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
			$sql="SELECT group_concat(user_id) AS user_ids FROM user_wise_service_order WHERE service_id='".$s_id."'";
			$result=$this->client_db->query($sql);
			// echo $this->client_db->last_query();die();
			if($result){
				return $result->row()->user_ids;
			}
			else{
				return '';
			}
		}
		else{
			return '';
		}
		
		
	}
}

?>