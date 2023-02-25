<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
    
	// ============================================================================
	// Common Script Start

	if ( !function_exists('assets_url') )
	{
		function assets_url()
	    {
	        $ci=& get_instance();
	        return $ci->config->item('assets_url');
	    }
	}

	if ( !function_exists('admin_url') )
	{
		function admin_url()
	    {
	        $ci=& get_instance();
	        return $ci->config->item('admin_url');
	    }
	}

	if ( !function_exists('file_upload_absolute_path') )
	{
		function file_upload_absolute_path()
	    {
	        $ci=& get_instance();
	        return $ci->config->item('file_upload_absolute_path');
	    }
	}	

	
    if ( !function_exists('is_access_denied') )
	{
		function is_access_denied($curr_reserved_keyword)
	    {
	        $ci=& get_instance();
	        $ses = $ci->session->userdata();
	            
	    }
	}

	if ( !function_exists('get_value') )
    {
    	function get_value($field,$tablename,$where='',$client_info=array())
	    {     
	    	$ci=& get_instance();
	    	$ci->load->database();
			$ci->fsas_db = $ci->load->database('default', TRUE); 
			$controller = $ci->router->fetch_class(); // class = controller
			// if($controller=='cronjobs' || $controller=='capture' || $controller=='rest_meeting')
			if(isset($client_info->db_name))
			{
				$config['hostname'] = DB_HOSTNAME;
		        $config['username'] = $client_info->db_username;
		        $config['password'] = $client_info->db_password;
		        $config['database'] = $client_info->db_name;
		        $config['dbdriver'] = 'mysqli';
		        $config['dbprefix'] = '';
		        $config['pconnect'] = FALSE;
		        $config['db_debug'] = TRUE;
		        $config['cache_on'] = FALSE;
		        $config['cachedir'] = '';
		        $config['char_set'] = 'utf8';
		        $config['dbcollat'] = 'utf8_general_ci'; 
		        $ci->client_db=$ci->load->database($config,TRUE);
			}
			else
			{
				$ci->client_db = $ci->load->database('client', TRUE);
			}
	        $fieldval="";
	        $sql="SELECT ".$field." FROM ".$tablename;
			if($where!="")
			{
				$sql = $sql." WHERE ".$where;
			}			
			$query=$ci->client_db->query($sql);			
			// if($query->num_rows())
			if($query)
			{	
				$result  = $query->row();
				$fieldval= $result->$field;
			}
			return $fieldval;
	    }
    }

    if ( !function_exists('date_display_format_to_db_format') )
	{
		function date_display_format_to_db_format($date)
	    {
	        $ci=& get_instance();
	        if($date!='')
	        {
	        	return date("Y-m-d",strtotime($date));
				// $date_arr = explode('/',$date);
				// return $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
	        }
	        else
	        {
	        	return false;
	        }
			
	    }
	}

	if ( !function_exists('datetime_display_format_to_db_format') )
	{
		function datetime_display_format_to_db_format($datetime)
	    {
	        $ci=& get_instance();
	        if($datetime!='')
	        {
				$datetime_new = strtotime(str_replace("/", "-", $datetime)); // DD/MM/YYYY h:m
        		return date("Y-m-d H:i:s",$datetime_new);
	        }
	        else
	        {
	        	return false;
	        }
	    }
	}

	if ( !function_exists('datetime_display_format_to_db_format_ampm') )
	{
		function datetime_display_format_to_db_format_ampm($datetime)
	    {
	        $ci=& get_instance();
	        if($datetime!='')
	        {	
				// $datetime_new = str_replace("/", "-", str_replace("AM", "", str_replace("PM", "", $datetime))); // DD/MM/YYYY h:m  
				$datetime_new = $datetime;       		
				return date("Y-m-d H:i:s", strtotime($datetime_new));
	        }
	        else
	        {
	        	return false;
	        }
	    }
	}
	
	if ( !function_exists('date_db_format_to_display_format') )
	{
		function date_db_format_to_display_format($date)
	    {
	        $ci=& get_instance();
	        if($date!='' && $date!='0000-00-00')
	        {
	        	//$date_arr = explode('-',$date);
				//return $date_arr[2].'/'.$date_arr[1].'/'.$date_arr[0];
				return date("d-M-Y",strtotime($date));
	        }
	        else
	        {
	        	return false;
	        }
			
	    }
	}
	
	if ( !function_exists('datetime_db_format_to_display_format_ampm') )
	{
		function datetime_db_format_to_display_format_ampm($datetime)
	    {
	        $ci=& get_instance();

	        if($datetime!='' && $datetime!='0000-00-00 00:00:00')
	        {	        	
	        	return date("d-M-Y g:i A",strtotime($datetime));
	        }
	        else
	        {
	        	return false;
	        }			
	    }
	}
	
	if ( !function_exists('datetime_db_format_to_display_format') )
	{
		function datetime_db_format_to_display_format($datetime)
	    {
	        $ci=& get_instance();

	        if($datetime!='' && $datetime!='0000-00-00 00:00:00')
	        {	        	
	        	//return date("d-M-Y g:i:s A",strtotime($datetime));
	        	return date("d-M-Y H:i:s",strtotime($datetime));
	        }
	        else
	        {
	        	return false;
	        }			
	    }
	}

	if ( !function_exists('time_db_format_to_display_format_ampm') )
	{
		function time_db_format_to_display_format_ampm($datetime)
	    {
	        $ci=& get_instance();

	        if($datetime!='' && $datetime!='0000-00-00 00:00:00')
	        {	        	
	        	return date("g:i A",strtotime($datetime));
	        }
	        else
	        {
	        	return false;
	        }			
	    }
	}

	if ( !function_exists('time_display_format_to_db_format_ampm') )
	{
		function time_display_format_to_db_format_ampm($datetime)
	    {
	        $ci=& get_instance();

	        if($datetime!='')
	        {	        	
	        	return date("H:i:s",strtotime($datetime));
	        }
	        else
	        {
	        	return false;
	        }			
	    }
	}

	if ( !function_exists('http_or_https_check') )
	{
		function http_or_https_check($string)
	    {
	        $ci=& get_instance();
	        $res='';
	        if(substr(trim($string), 0, 7) == "http://"){
			    $res = "http";
	        }
			else if(substr(trim($string), 0, 8) == "https://"){
			    $res = "https";	
			}

			return $res;
	    }
	}

	if ( !function_exists('validate_mobile') )
	{
		function validate_mobile($mobile)
	    {	
	        $ci=& get_instance();	        
	        if(preg_match('/^\d{10}$/',$mobile))
		    {
		      return true;
		    }
		    else // phone number is not valid
		    {
		      return false;
		    }
	    }
	}

	if ( !function_exists('validate_email') )
	{
		function validate_email($email)
	    {
	        $ci=& get_instance();
	        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
	        {
			 	return false;
			}
			else
			{
				return true;
			}
	    }
	}
    // Common script end
    // ============================================================================

	// ============================================================================
    // ADMIN FUNCTIONS
    if ( !function_exists('is_admin_logged_in') )
	{
		function is_admin_logged_in()
	    {
	    	$ci=& get_instance();
	    	
    		$admin_session_data = $ci->session->userdata('admin_session_data'); 
	        $is_admin_logged_in = $admin_session_data['is_admin_logged_in'];

	        if(!isset($is_admin_logged_in) || $is_admin_logged_in == FALSE)
	        {	
	        	if(isset($_COOKIE['lmsbaba_admin_user'])) 
		    	{
				    $tmp_client_id=$_COOKIE['lmsbaba_admin_user'];
	    			$client_id=file_get_contents("assets/uploads/clients/log/".$tmp_client_id."_client_id.txt");				    
				    @unlink("assets/uploads/clients/log/".$tmp_client_id."_client_id.txt");				    
				    redirect(admin_url().'login/'.$client_id);
				} 	            
	        }
	    }
	}

    if ( !function_exists('init_admin_login_element') )
	{
		function init_admin_login_element()
	    {
	    	$ci=& get_instance();
			//redirect(admin_url().'site_maintenance', 'refresh');
	    	if($ci->session->userdata('admin_session_data'))
			{
				if(count($ci->session->userdata('admin_session_data'))>0)
				{
					redirect(admin_url().'dashboard', 'refresh');
					exit(0);
				}				
			}
	    	init_admin_head();
	    }
	}

	if ( !function_exists('init_admin_element') )
	{
		function init_admin_element()
	    {
	    	init_admin_head();
	    }
	}

	if ( !function_exists('init_admin_head') )
	{
		function init_admin_head()
	    {
    		$ci=& get_instance();
	    	$data = array();

	    	$controller = $ci->router->fetch_class(); // class = controller
			$method = $ci->router->fetch_method();

			$data['controller'] = $controller;
			$data['method'] = $method;	
			
			// SET DATABASE DEFINE
			if($controller=='login')
			{	
				$ci->load->model(array('client_model'));
				// $client_id=$ci->uri->segment(3);
				// $client_info=$ci->client_model->get_details($client_id);
				$token=$ci->uri->segment(3);				
				if($token)
				{
					$client_info=$ci->client_model->get_details_by_token($token);
					// define("DB_USERNAME",$client_info->db_username);
					// define("DB_PASSWORD",$client_info->db_password);
					define("DB_NAME",$client_info->db_name);

					$data['page_title'] 		= $client_info->name.": Login";
					$data['page_keyword'] 		= $client_info->name.": Login";
					$data['page_description'] 	= $client_info->name.": Login";
					$data['session_info']=array();
					$data['company_info']=array();
					$data['lms_url']='';
				}
				else
				{					
					redirect('404_override', 'refresh');
					exit(0);
				}
			}
			else
			{	
				$admin_session_data=$ci->session->userdata('admin_session_data');
				$client_id=$admin_session_data['client_id'];
				$user_id=$admin_session_data['user_id'];
				//$client_info=$ci->client_model->get_details($client_id);
				$client_info=$admin_session_data['client_info'];

				// define("DB_USERNAME",$client_info->db_username);
		        // define("DB_PASSWORD",$client_info->db_password);
		        define("DB_NAME",$client_info->db_name);

		        $data['page_title'] 	= "Welcome to ".$client_info->name;
				$data['page_keyword'] 	= "Welcome to ".$client_info->name;
				$data['page_description'] = "Welcome to ".$client_info->name;
				$data['session_info']= $admin_session_data;	
				//$data['company_info']=get_company_profile();
				$data['company_info']=$ci->session->userdata('company_info');
				$data['lms_url']=$admin_session_data['lms_url'].'/';

				$ci->load->model(array('Setting_model'));
				$data['idle_info']=$ci->Setting_model->GetIdleInfo($client_info) ;

				
				// ====================================================================================
				$label_alias_file_url=file_upload_absolute_path().'assets/uploads/clients/'.$client_info->client_id.'/label_alias/default.txt';
				if (file_exists($label_alias_file_url)) {
					$label_alias_content=file_get_contents($label_alias_file_url);				
					$data['menu_label_alias']=json_decode($label_alias_content,true);
				} else {					
					$data['menu_label_alias']=get_menu_label_alias();					
				}				
				// ====================================================================================		        	        
			}

			$data['client_info']= $client_info;
			// SET META INFO
			// if($controller=='login')
			// {				
			// 	$data['page_title'] 		= $client_info->name.": Login";
			// 	$data['page_keyword'] 		= $client_info->name.": Login";
			// 	$data['page_description'] 	= $client_info->name.": Login";
			// 	$data['session_info']=array();
			// 	$data['company_info']=array();
			// 	$data['lms_url']='';
			// }	
			// else
			// {				
			// 	$data['page_title'] 	= "Welcome to ".$client_info->name;
			// 	$data['page_keyword'] 	= "Welcome to ".$client_info->name;
			// 	$data['page_description'] = "Welcome to ".$client_info->name;
			// 	$data['session_info']= $admin_session_data;	
			// 	$data['company_info']=get_company_profile();
			// 	$data['lms_url']=$admin_session_data['lms_url'].'/';
			// }	
			
			$ci->load->library('user_agent');
	        $mobile=$ci->agent->is_mobile();
			if($mobile)
			{
			    $data['is_mobile']="Y";
			}
			else
			{
				$data['is_mobile']="N";
			}

			$data['service_wise_menu']=$ci->session->userdata('service_wise_menu');
			$data['user_wise_service_info']=$admin_session_data['service_info'];
			$is_show_lead_management_service_menu='N';
			$is_show_hrm_menu='N';
			
			// if(count($data['user_wise_service_info']['all_available_service']))
			if(isset($data['user_wise_service_info']['all_available_service']))
			{	
				foreach($data['user_wise_service_info']['all_available_service'] AS $all_available_service)
				{					
					if(in_array($all_available_service['service_id'],$data['user_wise_service_info']['service_tagged']))
					{
						if($all_available_service['service_id']=='1' && $is_show_lead_management_service_menu=='N'){
							$is_show_lead_management_service_menu='Y';							
						}
						if($all_available_service['service_id']=='2' && $is_show_hrm_menu=='N'){
							$is_show_hrm_menu='Y';							
						}
					}					
				}
			}
			$data['is_show_lead_management_service_menu']=$is_show_lead_management_service_menu;
			$data['is_show_hrm_menu']=$is_show_hrm_menu;



			// $data['package_id']=$ci->session->userdata('package_id');
			// $data['package_order_id']=$ci->session->userdata('package_order_id');
			// $data['package_data']=$ci->session->userdata('package_data');
			// $data['menu']=$ci->session->userdata('menu');			 
			// $data['permission_data']=$ci->session->userdata('permission_data');
			$ci->load->view('admin/includes/head',$data,true);
	    }
	}
	// ADMIN FUNCTIONS
    // ============================================================================
    
    // ============================================================================
    // USER PERMISSION CHECK

    if ( !function_exists('is_method_available') )
    {
    	function is_method_available($controller,$method)
	    {     
			return TRUE;
	    	// $ci=& get_instance();
	    	// $ci->load->database();  
	    	// $ci->client_db = $ci->load->database('client', TRUE);
			// $ci->fsas_db = $ci->load->database('default', TRUE);

	    	// $all_session = $ci->session->userdata();
        	// $logged_in_info = $all_session['admin_session_data'];

        	// if($logged_in_info['user_id']==1)
        	// {
        	// 	return TRUE;
        	// }
        	// else
        	// {
        	// 	$sql="SELECT id FROM tbl_user_permission_controller_method WHERE user_id='".$logged_in_info['user_id']."' AND controller='".$controller."' AND method='".$method."'";			
			
			// 	$query = $ci->client_db->query($sql);				
			// 	if($query){
			// 		if($query->num_rows()>0)
			// 		{	
			// 			return TRUE;
			// 		}
			// 		else
			// 		{
			// 			return FALSE;
			// 		}
			// 	}
			// 	else{
			// 		return FALSE;
			// 	}
				
        	// }
	    }
    }

    if ( !function_exists('is_attribute_available') )
    {
    	function is_attribute_available($reserve_key)
	    {     
	    	$ci=& get_instance();
	    	$ci->load->database(); 
	    	$ci->client_db = $ci->load->database('client', TRUE);
			$ci->fsas_db = $ci->load->database('default', TRUE); 
	    	$all_session = $ci->session->userdata();
        	$logged_in_info = $all_session['admin_session_data'];
        	$package_id = $all_session['package_id'];
        	$package_order_id = $all_session['package_order_id'];
        	
        	if($logged_in_info['user_id']==1)
        	{
        		$sql="SELECT id FROM tbl_package_order_detail WHERE package_order_id='".$package_order_id."' AND reserved_keyword='".$reserve_key."'";
        		$query = $ci->client_db->query($sql);
        		if($query){
					if($query->num_rows()>0)
					{	
						return TRUE;
					}
					else
					{
						return FALSE;
					}
				}
				else{
					return FALSE;
				}
        		
        	}
        	else
        	{ 
        		
        		$sql="SELECT id FROM tbl_user_permission WHERE user_id='".$logged_in_info['user_id']."' AND reserved_keyword='".$reserve_key."'";			
				
				$query = $ci->client_db->query($sql);	
				if($query){
					if($query->num_rows()>0)
					{	
						return TRUE;
					}
					else
					{
						return FALSE;
					}
				}	
				else{
					return FALSE;
				}
        	}
	    }
    }
    
  
    if ( !function_exists('package_attribute_value') )
    {
    	function package_attribute_value($reserve_key)
	    {     
	    	$ci=& get_instance();
	    	$ci->load->database();  
	    	$ci->client_db = $ci->load->database('client', TRUE);
			$ci->fsas_db = $ci->load->database('default', TRUE);
	    	$all_session = $ci->session->userdata();
        	$logged_in_info = $all_session['admin_session_data'];

        	
        		$sql="SELECT * FROM tbl_user_permission WHERE user_id='".$logged_in_info['user_id']."' AND reserved_keyword='".$reserve_key."'";			
			
				$query = $ci->client_db->query($sql);
				//echo $ci->db->last_query();
				if($query){
					if($query->num_rows()>0)
					{	
						return $query->result();
					}
					else
					{
						return FALSE;
					}
				}
				else{
					return FALSE;
				}
				
        	
	    }
    }

    // ============================================================================
    // USER PERMISSION CHECK



    // ============================================================================
    if ( !function_exists('is_logged_in') )
	{
		function is_logged_in()
	    {
	    	$ci=& get_instance();	    	
    		$session_data = $ci->session->userdata('logged_in'); 
	        $is_logged_in = $session_data['is_logged_in'];

	        if(!isset($is_logged_in) || $is_logged_in == FALSE)
	        {	
	        	//$redirect=str_replace('development/','',base_url());
				//redirect($redirect.'lmsportal/user/logout/');
	            redirect(base_url());
	        }        
	    }
	}

	if ( !function_exists('init_element') )
	{
		function init_element()
	    {
	    	init_head();	    	
	    }
	}

	if ( !function_exists('init_head') )
	{
		function init_head()
	    {
    		$ci=& get_instance();
	    	$data = array();    	

	    	$controller = $ci->router->fetch_class(); // class = controller
			$method = $ci->router->fetch_method();
			// echo $method;
			// -----------------------------------------------------------
			// restricted validate
			$controller_restriction_arr=array('dashboard','lead','customer','product','vendor','user');
			if(in_array($controller, $controller_restriction_arr))
			{
				if($controller=='user'){
					$user_method_restriction_arr=array('manage_employee','add_employee','edit_employee','manage_department','manage_designation','manage_functional_area');
					if(in_array($method, $user_method_restriction_arr)){
						is_logged_in();
					}
				}
				else{
					is_logged_in();
				}
			}
			// restricted validate
			// -----------------------------------------------------------
			

			$data['controller'] = $controller;
			$data['method'] = $method;			
			$data['page_title'] 		= "LMS - Baba";
			$data['page_keyword'] 		= "LMS - Baba";
			$data['page_description'] 	= "LMS - Baba";

			// $session_data = $ci->session->userdata('logged_in'); 
			//      	$user_id=$session_data['user_id'];
			// if($user_id)
			// {
			// 	$ci->load->model(array('menu_model','notification_model'));
			// 	$data['user_menu_list']=$ci->menu_model->GetMenuListByUser($user_id) ;
			// 	$data['notification_list']=$ci->notification_model->GetNotificationList('10','0',$user_id);     	
			// 	$data['notification_count']=$ci->notification_model->GetUnreadNotificationCount($user_id);
			// 	$ci->load->view('include/sidebar',$data,true);
			// }			
	    }
	}	

	if ( !function_exists('inserted_lead_comment_log') )
    {
    	function inserted_lead_comment_log($data)
	    {     
	    	$ci=& get_instance();
	    	$ci->load->database(); 
	    	$ci->client_db = $ci->load->database('client', TRUE);
			$ci->fsas_db = $ci->load->database('default', TRUE); 
	    	if($ci->client_db->insert('lead_comment',$data))
	   		{
	           return true;
	   		}
	   		else
	   		{
	          return false;
	   		}
	    }
    }

    if ( !function_exists('number_to_word') )
    {
    	function number_to_word($number)
	    {     
			$ci=& get_instance();
			$no = floor($number);
			$point = round($number - $no, 2) * 100;
			$hundred = null;
			$digits_1 = strlen($no);
			$i = 0;
			$str = array();
			$words = array(
						'0' => '', 
						'1' => 'one', 
						'2' => 'two',
						'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
						'7' => 'seven', '8' => 'eight', '9' => 'nine',
						'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
						'13' => 'thirteen', '14' => 'fourteen',
						'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
						'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
						'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
						'60' => 'sixty', '70' => 'seventy',
						'80' => 'eighty', '90' => 'ninety'
						);
			$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
			while ($i < $digits_1) 
			{
				$divider = ($i == 2) ? 10 : 100;
				$number = floor($no % $divider);
				$no = floor($no / $divider);
				$i += ($divider == 10) ? 1 : 2;
				if ($number) 
				{
					$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
					$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
					$str [] = ($number < 21) ? $words[$number] .
					" " . $digits[$counter] . $plural . " " . $hundred
					:
					$words[floor($number / 10) * 10]
					. " " . $words[$number % 10] . " "
					. $digits[$counter] . $plural . " " . $hundred;
				} 
				else 
					$str[] = null;
			}
			$str = array_reverse($str);
			$result = implode('', $str);
			$points = ($point) ?
			"." . $words[$point / 10] . " " . 
			$words[$point = $point % 10] : '';
			//return $result . "Rupees  " . $points . " Paise";
			$str=$result . " " . $points . " Only";
			return ucwords($str);
	    }
    }

    if ( !function_exists('get_company_profile') )
	{
		function get_company_profile($client_info=array())
	    {	
    		$ci=& get_instance();
			$ci->load->model(array('Setting_model'));
			return $ci->Setting_model->GetCompanyData($client_info) ;			
	    }
	}

	if ( !function_exists('get_manager_and_skip_manager_email') )
	{
		function get_manager_and_skip_manager_email($user_id)
	    {
    		$ci=& get_instance();
			$ci->load->model(array('user_model'));
			$get_immidiate_manager_id=0;
			$get_manager_of_immidiate_manager_id=0;
			$get_immidiate_manager_id=$ci->user_model->get_manager_id($user_id);
			if($get_immidiate_manager_id>0)
			{
				$get_manager_of_immidiate_manager_id=$ci->user_model->get_manager_id($get_immidiate_manager_id);
			}
			
			$cc_mail_arr=array();
			// if($get_immidiate_manager_id>0 && $get_immidiate_manager_id!=1)
			if($get_immidiate_manager_id>0)
			{
				$immidiate_manager_email=get_value("email","user","id=".$get_immidiate_manager_id);
				array_push($cc_mail_arr, $immidiate_manager_email);
			}
			// if($get_manager_of_immidiate_manager_id>0 && $get_manager_of_immidiate_manager_id!=1)
			if($get_manager_of_immidiate_manager_id>0)
			{
				$manager_of_immidiate_manager_email=get_value("email","user","id=".$get_manager_of_immidiate_manager_id);
				array_push($cc_mail_arr, $manager_of_immidiate_manager_email);
			}
			
			$final_cc_mail_str='';
			$final_cc_mail_str=implode(",", $cc_mail_arr);
			return $final_cc_mail_str;		
	    }
	}

	if ( !function_exists('get_manager_and_skip_manager_email_arr') )
	{
		function get_manager_and_skip_manager_email_arr($user_id,$client_info=array())
	    {

    		$ci=& get_instance();
			$ci->load->model(array('user_model'));
			$manager_email='';
			$skip_manager_email='';
			$get_immidiate_manager_id=0;
			$get_manager_of_immidiate_manager_id=0;
			$get_immidiate_manager_id=$ci->user_model->get_manager_id($user_id,$client_info);
			if($get_immidiate_manager_id>0)
			{
				$get_manager_of_immidiate_manager_id=$ci->user_model->get_manager_id($get_immidiate_manager_id,$client_info);
			}
			
			$cc_mail_arr=array();
			//if($get_immidiate_manager_id>0 && $get_immidiate_manager_id!=1)
			if($get_immidiate_manager_id>0)
			{
				$immidiate_manager_email=get_value("email","user","id=".$get_immidiate_manager_id,$client_info);
				array_push($cc_mail_arr, $immidiate_manager_email);
				$manager_email=$immidiate_manager_email;
			}

			

			// if($get_manager_of_immidiate_manager_id>0 && $get_manager_of_immidiate_manager_id!=1)
			if($get_manager_of_immidiate_manager_id>0)
			{
				$manager_of_immidiate_manager_email=get_value("email","user","id=".$get_manager_of_immidiate_manager_id,$client_info);
				array_push($cc_mail_arr, $manager_of_immidiate_manager_email);
				$skip_manager_email=$manager_of_immidiate_manager_email;
			}

			$return=array(
					'manager_email'=>$manager_email,
					'skip_manager_email'=>$skip_manager_email
					);
			return $return;		
	    }
	}

	if ( !function_exists('get_manager_and_skip_manager_mobile_arr') )
	{
		function get_manager_and_skip_manager_mobile_arr($user_id,$client_info=array())
	    {

    		$ci=& get_instance();
			$ci->load->model(array('user_model'));
			$manager_email='';
			$skip_manager_email='';
			$get_immidiate_manager_id=0;
			$get_manager_of_immidiate_manager_id=0;
			$get_immidiate_manager_id=$ci->user_model->get_manager_id($user_id,$client_info);
			
			if($get_immidiate_manager_id>0)
			{
				$get_manager_of_immidiate_manager_id=$ci->user_model->get_manager_id($get_immidiate_manager_id,$client_info);				
			}
			
			$cc_mail_arr=array();
			// if($get_immidiate_manager_id>0 && $get_immidiate_manager_id!=1)
			if($get_immidiate_manager_id>0)
			{
				$immidiate_manager_email=get_value("mobile","user","id=".$get_immidiate_manager_id,$client_info);
				array_push($cc_mail_arr, $immidiate_manager_email);
				$manager_email=$immidiate_manager_email;
			}

			

			// if($get_manager_of_immidiate_manager_id>0 && $get_manager_of_immidiate_manager_id!=1)
			if($get_manager_of_immidiate_manager_id>0)
			{
				$manager_of_immidiate_manager_email=get_value("mobile","user","id=".$get_manager_of_immidiate_manager_id,$client_info);
				array_push($cc_mail_arr, $manager_of_immidiate_manager_email);
				$skip_manager_email=$manager_of_immidiate_manager_email;
			}

			$return=array(
					'manager_mobile'=>$manager_email,
					'skip_manager_mobile'=>$skip_manager_email
					);
			return $return;		
	    }
	}

	// ========================================================================

	if ( !function_exists('get_regret_reason') )
    {
    	function get_regret_reason($client_info=array())
	    {

	    	$ci=& get_instance();
			$ci->load->database();
			$ci->fsas_db = $ci->load->database('default', TRUE);   

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
				$ci->client_db=$ci->load->database($config,TRUE);
			} else {
				$ci->client_db = $ci->load->database('client', TRUE);
			}
	    	
			      	
        	$sql="SELECT * FROM opportunity_regret_reason WHERE 1=1";
			$query = $ci->client_db->query($sql);
			//echo $ci->db->last_query();
			if($query){
				if($query->num_rows()>0)
				{	
					return $query->result_array();
				}
				else
				{
					return FALSE;
				}
			}
			else{
				return FALSE;
			}
	    }
    }

    if ( !function_exists('status_text') )
    {
    	function status_text($status)
	    {     
	    	if ($status==0) {
	    		return 'Approved';
	    	}elseif($status==1){
	    		return 'Rejected';
	    	}else{
	    		return 'Blacklisted';
	    	}
        	
	    }
    }

    if ( !function_exists('get_first_num_of_words') )
	{
		function get_first_num_of_words($string, $num_of_words)
	    {
	        $ci=& get_instance();
	        $string = preg_replace('/\s+/', ' ', trim($string));
	        $words = explode(" ", $string); // an array

	        // if number of words you want to get is greater than number of words in the string
	        if ($num_of_words > count($words)) {
	            // then use number of words in the string
	            $num_of_words = count($words);
	        }

	        $new_string = "";
	        for ($i = 0; $i < $num_of_words; $i++) {
	            $new_string .= $words[$i] . " ";
	        }

	        return trim($new_string);
	    }
	}
	
	if ( !function_exists('get_company_name_initials') )
    {
    	function get_company_name_initials($client_info=array())
	    {
	    	$ci=& get_instance();
	    	$controller = $ci->router->fetch_class(); // class = controller		

	    	$ci->load->model(array('Setting_model'));
	    	$company_data=$ci->Setting_model->GetCompanyData($client_info);	
	    	$company_name_tmp = "";	
	    	if($company_data['is_system_generated_enquiryid_logic']=='N'){ 
	    		$company_name_tmp = $company_data['enquiryid_initial'];
	    	}	
	    	
	    	if($company_name_tmp=='')
	    	{
	    		$words = explode(" ", $company_data['name']);				
				foreach ($words as $w) {
				  $company_name_tmp .= strtoupper($w[0]);
				}
	    	}			
			return $company_name_tmp;       	
	    }
    }

    if ( !function_exists('get_indiamart_setting') )
	{
		function get_indiamart_setting()
	    {
    		$ci=& get_instance();
			return array('glusr_mobile'=>'7042445112','glusr_mobile_key'=>'MTU5MTE1OTgyMS44MzAyIzcyMzEyNjE=');			
	    }
	}
	
	if ( !function_exists('rander_company_address') )
	{
		function rander_company_address($view_location='',$client_info=array())
	    {
    		$ci=& get_instance();
			$company=get_company_profile($client_info);
			$address_str='';
			$address_str .=$company['address'];			
			if(trim($company['address']) && trim($company['city_name'])){$address_str .=', ';}
			if(trim($company['city_name'])){$address_str .= $company['city_name'];}
			if(trim($company['city_name']) && trim($company['state_name'])){$address_str .= ', ';}
			if(trim($company['state_name'])){$address_str .=$company['state_name'].' - '.$company['pin'];}
			if(trim($company['state_name']) && trim($company['country_name'])){$address_str .= ', ';}
			if(trim($company['country_name'])){$address_str .=$company['country_name'];}
			if($view_location=='preview_quotation' || $view_location=='generate_quotation_pdf' || $view_location=='generate_quotation')
			{
				$address_str .='<br>';
				//$address_str .=($company['mobile1'])?' Mobile/WhatsApp: '.$company['mobile1']:'';
				$address_str .=($company['phone1'])?' Phone: '.$company['phone1']:'';
				$address_str .=($company['phone2'])?'/ '.$company['phone2']:'';
			}

			if($view_location=='email_template')
			{
				$address_str .='<br>';
				//$address_str .=($company['mobile1'])?' Mobile/WhatsApp: '.$company['mobile1']:'';
				$address_str .=($company['phone1'])?' Phone: '.$company['phone1']:'';
				$address_str .=($company['phone2'])?'/ '.$company['phone2']:'';
				$address_str .=($company['email1'])?' Email: '.$company['email1']:'';
				$address_str .=($company['website'])?' Website: '.$company['website']:'';
			}

			if($view_location=='email_template_best_regards')
			{
				$address_str .='<br>';
				//$address_str .=($company['mobile1'])?' Mobile/WhatsApp: '.$company['mobile1']:'';
				$address_str .=($company['phone1'])?' Phone: '.$company['phone1']:'';
				$address_str .=($company['phone2'])?'/ '.$company['phone2']:'';
				$address_str .=($company['email1'])?'<br>Email: '.$company['email1']:'';
				$address_str .=($company['website'])?'<br>Website: '.$company['website']:'';
			}
			
			echo $address_str;
			
	    }
	}

	if ( !function_exists('rander_company_address_cronjobs') )
	{
		function rander_company_address_cronjobs($view_location='',$client_info=array())
	    {
    		$ci=& get_instance();
			$company=get_company_profile($client_info);
			$address_str='';
			$address_str .=$company['address'];			
			if(trim($company['address']) && trim($company['city_name'])){$address_str .=', ';}
			if(trim($company['city_name'])){$address_str .= $company['city_name'];}
			if(trim($company['city_name']) && trim($company['state_name'])){$address_str .= ', ';}
			if(trim($company['state_name'])){$address_str .=$company['state_name'].' - '.$company['pin'];}
			if(trim($company['state_name']) && trim($company['country_name'])){$address_str .= ', ';}
			if(trim($company['country_name'])){$address_str .=$company['country_name'];}
			
			if($view_location=='email_template')
			{
				$address_str .='<br>';
				//$address_str .=($company['mobile1'])?' Mobile/WhatsApp: '.$company['mobile1']:'';
				$address_str .=($company['phone1'])?' Phone: '.$company['phone1']:'';
				$address_str .=($company['phone2'])?'/ '.$company['phone2']:'';
				$address_str .=($company['email1'])?' Email: '.$company['email1']:'';
				$address_str .=($company['website'])?' Website: '.$company['website']:'';
			}

			if($view_location=='email_template_best_regards')
			{
				$address_str .='<br>';
				//$address_str .=($company['mobile1'])?' Mobile/WhatsApp: '.$company['mobile1']:'';
				$address_str .=($company['phone1'])?' Phone: '.$company['phone1']:'';
				$address_str .=($company['phone2'])?'/ '.$company['phone2']:'';
				$address_str .=($company['email1'])?'<br>Email: '.$company['email1']:'';
				$address_str .=($company['website'])?'<br>Website: '.$company['website']:'';
			}
			
			return $address_str;
			
	    }
	}

	if ( !function_exists('get_months') )
	{
		function get_months($set_no_of_month=12)
	    {
			$ci=& get_instance();
			$start = new DateTime('first day of this month - '.($set_no_of_month-1).' months');
			$end   = new DateTime('last day of this month');
			$interval  = new DateInterval('P1M'); // http://www.php.net/manual/en/class.dateinterval.php
			$date_period = new DatePeriod($start, $interval, $end);
			$months = array();
			foreach($date_period as $dates) {
				$months[$dates->format('Y').'-'.$dates->format('m')]= $dates->format('M').', '.$dates->format('Y');
			}
			return array_reverse($months);

			// $start = new DateTime;			
			// $start->setDate($start->format('Y'), $start->format('n'), 1); // Normalize the day to 1
			// $start->setTime(0, 0, 0); // Normalize time to midnight
			// $start->sub(new DateInterval('P12M'));
			// $interval = new DateInterval('P1M');
			// $recurrences=12
			// foreach (new DatePeriod($start, $interval, $recurrences, true) as $date) {
			// 	$months[$date->format('Y-m')] = $date->format('F, Y'); // attempting to make it more clear to read here
			// }
			// return array_reverse($months);
			/*
			$curr_month_count = date('m');
			$curr_fy = date('Y');
			$months =array(
						1 => 'Jan., '.$curr_fy, 
						2 => 'Feb., '.$curr_fy, 
						3 => 'Mar., '.$curr_fy, 
						4 => 'Apr., '.$curr_fy, 
						5 => 'May, '.$curr_fy, 
						6 => 'Jun., '.$curr_fy, 
						7 => 'Jul., '.$curr_fy, 
						8 => 'Aug., '.$curr_fy, 
						9 => 'Sep., '.$curr_fy, 
						10 => 'Oct., '.$curr_fy, 
						11 => 'Nov., '.$curr_fy, 
						12 => 'Dec., '.$curr_fy
					);
			$transposed = array_slice($months, date('n'), 12, true) + array_slice($months, 0, date('n'), true);
			$month_list = array_reverse(array_slice($transposed, -$curr_month_count, 12, true), true);
			return $month_list;
			*/
			
	    }
	}
	

	if ( !function_exists('get_fy') )
	{
		function get_fy($set_no_of_year=5)
	    {
			$ci=& get_instance();
			for($i=0;$i<$set_no_of_year;$i++)
			{
				$years[date("Y",strtotime("-$i year"))]=date("Y",strtotime("-$i year"));
			}
			return $years;			
	    }
	}

	if ( !function_exists('get_fy_rows') )
	{
		function get_fy_rows($set_no_of_year=5)
	    {
			$ci=& get_instance();
			$year_arr=[];
			// for($i=0;$i<$set_no_of_year;$i++)
			// {
			// 	array_push($year_arr,date("Y",strtotime("-$i year")));   
			// }

			if(date("m")>3){
				$i=0;
			} else {
				$i=1;
				$set_no_of_year=$set_no_of_year+1;
			}

			while($i<$set_no_of_year)
			{
				array_push($year_arr,date("Y",strtotime("-$i year")));
				$i++;   
			}

			$fn_month = 04;
			foreach ($year_arr as $year) 
			{
				$end_date = new DateTime($year . '-' . $fn_month . '-01');
				$start_date = clone $end_date;    
				$end_date->modify('+1 year -1 day');

				$fy_tmp = new DateTime($year . '-' . $fn_month . '-01');
				$fy_tmp->modify('+1 year');
				$fy=$year.'-'.$fy_tmp->format('Y');
				$date_range_arr[] = array(
										'fy'=>$fy,
										'start_date' => $start_date->format('Y-m-d'), 
										'end_date' => $end_date->format('Y-m-d')
										);
			}			
			return $date_range_arr;			
	    }
	}

	if ( !function_exists('get_source') )
	{
		function get_source()
	    {
    		$ci=& get_instance();
			$ci->load->model(array('Source_model'));
			return $ci->Source_model->get_source() ;			
	    }
	}
	if ( !function_exists('rander_extention_wise_image') )
	{
		function rander_extention_wise_image($ext)
	    {
    		$ci=& get_instance();
    		$image_arr=array('jpg','jpeg','gif','png');
    		$pdf_arr=array('pdf');
    		$doc_arr=array('doc','docx','txt');
    		$excel_arr=array('xlsx','xls','csv');
			if(in_array(strtolower($ext), $image_arr))
			{
				echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
			}	
			else if(in_array(strtolower($ext), $pdf_arr))
			{
				echo '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>';
			}
			else if(in_array(strtolower($ext), $pdf_arr))
			{
				echo '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>';
			}
			else if(in_array(strtolower($ext), $doc_arr))
			{
				echo '<i class="fa fa-file-text-o" aria-hidden="true"></i>';
			}
			else if(in_array(strtolower($ext), $excel_arr))
			{
				echo '<i class="fa fa-file-excel-o" aria-hidden="true"></i>';
			}
			else
			{
				echo '<i class="fa fa-file-text-o" aria-hidden="true"></i>';
			}
	    }
	}

	if ( !function_exists('get_gmail_token') )
	{
		function get_gmail_token()
	    {
    		$ci=& get_instance();
    		$session_data = $ci->session->userdata('admin_session_data');
            $user_id = $session_data['user_id'];		
			
			if(CODEBASE_ENV=='dev')
			{
				return $_SERVER['DOCUMENT_ROOT']."/".CODEBASE_ENV."/assets/uploads/clients/".$session_data['client_id']."/gmail_api/".$user_id."_token.json";
			}
			else if(CODEBASE_ENV=='stg')
			{
				return $_SERVER['DOCUMENT_ROOT']."/".CODEBASE_ENV."/assets/uploads/clients/".$session_data['client_id']."/gmail_api/".$user_id."_token.json";
			}
			else
			{
				return $_SERVER['DOCUMENT_ROOT']."/assets/uploads/clients/".$session_data['client_id']."/gmail_api/".$user_id."_token.json";
			}
						
	    }
	}

	if ( !function_exists('get_gmail_credentials') )
	{
		function get_gmail_credentials()
	    {
    		$ci=& get_instance();			
			
			if(CODEBASE_ENV=='dev')
			{
				return $_SERVER['DOCUMENT_ROOT']."/".CODEBASE_ENV."/assets/uploads/clients/log/credentials.json" ;	
			}
			else if(CODEBASE_ENV=='stg')
			{
				return $_SERVER['DOCUMENT_ROOT']."/".CODEBASE_ENV."/assets/uploads/clients/log/credentials.json" ;	
			}
			else
			{
				return $_SERVER['DOCUMENT_ROOT']."/assets/uploads/clients/log/credentials.json" ;	
			}				
	    }
	}

	if ( !function_exists('datetime_db_format_to_gmail_format') )
	{
		function datetime_db_format_to_gmail_format($datetime)
	    {
	        $ci=& get_instance();
	        $return_date='';
	        if($datetime!='' && $datetime!='0000-00-00 00:00:00')
	        {	       
	        	if(date("Y",strtotime($datetime))==date('Y'))
	        	{
	        		if(date("d",strtotime($datetime))==date('d'))
	        		{
	        			$return_date=date("h:i A",strtotime($datetime));
	        		}
	        		else
	        		{
	        			$return_date=date("M d",strtotime($datetime));
	        		}
	        	} 
	        	else if(date("Y",strtotime($datetime))<date('Y'))
	        	{
	        		$return_date=date("d/m/Y",strtotime($datetime));
	        	}	
	        	//return date("d-M-Y g:i:s A",strtotime($datetime));
	        	// return date("d-M-Y h:i A",strtotime($datetime));
	        	return $return_date;
	        }
	        else
	        {
	        	return false;
	        }			
	    }
	}

	if ( !function_exists('datetime_db_format_to_gmail_details_format') )
	{
		function datetime_db_format_to_gmail_details_format($datetime)
	    {
	        $ci=& get_instance();
	        $return_date='';
	        if($datetime!='' && $datetime!='0000-00-00 00:00:00')
	        {	       
	        	if(date("Y",strtotime($datetime))==date('Y'))
	        	{
	        		if(date("d",strtotime($datetime))==date('d'))
	        		{
	        			$return_date=date("h:i A",strtotime($datetime));
	        		}
	        		else
	        		{
	        			$return_date=date("D, M d, h:i A",strtotime($datetime));
	        		}
	        	} 
	        	else if(date("Y",strtotime($datetime))<date('Y'))
	        	{
	        		$return_date=date("d/m/Y, h:i A",strtotime($datetime));
	        	}	
	        	//return date("d-M-Y g:i:s A",strtotime($datetime));
	        	// return date("d-M-Y h:i A",strtotime($datetime));
	        	return $return_date;
	        }
	        else
	        {
	        	return false;
	        }			
	    }
	}

	if ( ! function_exists('get_api_access_token')) 
	{
		function get_api_access_token()
		{
			$ci =& get_instance();
			 $access_token = md5($ci->config->item('api_access_token'));
	    	 return $access_token;
	    }
	}

	if ( ! function_exists('push_notification_android')) 
	{
		function push_notification_android($device_id,$title,$message,$notification_post)
		{
			$ci =& get_instance();
			$api_access_key='AAAA_x_djiQ:APA91bG6VLhtw9eSadgoeGK8N9rbqTUv5_qHOSLhbb1yTN18pKKort1vVGJO-rd6D1GWqspy9zLVr5xN9xww76H12rsyl88HCosYpZAKi8MN-QRIO3hO5C7feuWN2F7gNbmVRrIPMkKo';
			$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
			$token=$device_id;
			$notification = [
			    'title' =>$title,
			    'body' => $message,
			    'icon' =>'myIcon', 
			    'sound' => 'mySound'
			];
			$extraNotificationData = ["message" => $notification,"moredata" =>$notification_post];
	        $fcmNotification = [
	            //'registration_ids' => $tokenList, //multple token array
	            'to'        => $token, //single token
	            'notification' => $notification,
	            'data' => $extraNotificationData
	        ];

	        $headers = [
	            'Authorization: key=' . $api_access_key,
	            'Content-Type: application/json'
	        ];


	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
	        $result = curl_exec($ch);
	        curl_close($ch);
	       return (array) json_decode($result,true);				    
	    }
	}

	

	if ( !function_exists('rander_whatsapp_variable') )
	{
		function rander_whatsapp_variable($message='',$variable_info=array(),$client_info=array())
	    {
    		$ci=& get_instance();
			$ci->load->model(array('Setting_model','customer_model','lead_model','user_model'));
			$get_template_variable_list=get_template_variable_list($client_info);
			
			if(count($get_template_variable_list)>0 && $message!='' && count($variable_info)>0)
			{
				foreach($get_template_variable_list AS $variable)
				{
					if (strpos($message, $variable['reserve_keyword']) !== false) {
						if($variable['variable_type']=='buyer_details' && $variable_info['customer_id']!='')
						{
							$customer_info=$ci->customer_model->sms_customer_row($variable_info['customer_id'],$client_info);
							if(count($customer_info))
							{
								$replaced_str=$customer_info[trim($variable['reserve_keyword'],'#')];
								if($replaced_str)
								{
									$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
								}
							}							
						}

						if($variable['variable_type']=='company' && $variable_info['company_id']!='')
						{
							$company_info=$ci->Setting_model->sms_company_row($client_info);
							if(count($company_info))
							{
								$replaced_str=$company_info[trim($variable['reserve_keyword'],'#')];
								if($replaced_str)
								{
									$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
								}
							}							
						}

						if($variable['variable_type']=='lead_details' && $variable_info['lead_id']!='')
						{
							$lead_info=$ci->lead_model->sms_lead_row($variable_info['lead_id'],$client_info);
							if(count($lead_info))
							{
								$replaced_str=$lead_info[trim($variable['reserve_keyword'],'#')];
								if($replaced_str)
								{
									$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
								}
							}							
						}

						if($variable['variable_type']=='user_details' && $variable_info['user_id']!='')
						{
							$user_info=$ci->user_model->sms_user_row($variable_info['user_id'],$client_info);
							if(count($user_info))
							{
								$replaced_str=$user_info[trim($variable['reserve_keyword'],'#')];
								if($replaced_str)
								{
									$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
								}
							}							
							
						}						
					}
				}
			}
			return $message;		
	    }
	}
	if ( !function_exists('send_template_message_by_whatsapp') )
	{
		function send_template_message_by_whatsapp($header='lmsbaba',$whatsapp_send_data,$whatsapp_variable_info=array(),$client_info=array())
	    {
	        $ci=& get_instance();	
			$ci->load->model(array('Whatsapp_setting_model','customer_model','lead_model','user_model'));
			
			$return_arr=array();			
			foreach($whatsapp_send_data AS $whatsapp_row)
			{
				if($whatsapp_row['mobile']!='' && $whatsapp_row['template_auto_id']!='')
				{
					$mobile_tmp=$whatsapp_row['mobile'];
					if(strlen($mobile_tmp)==10)
					{
						$mobile_tmp='91'.$mobile_tmp;
					}					
					$template_info=$ci->Whatsapp_setting_model->GetTemplateDetails($whatsapp_row['template_auto_id'],$client_info);	
					
					if(count($template_info))
					{
						if($template_info['api_whatsapp_service_provider_id']=='1') //Pinnacle WhatsApp Solution
						{
							$url='https://api.pinbot.ai/v1/wamessage/sendMessage';			
							$apikey=$template_info['api_apikey'];
							$from=$template_info['api_sender'];
							$to=$mobile_tmp;
							$templateid=$template_info['template_id'];  
							$placeholders='';
						 
							if($template_info['template_variable']){
								$placeholders_tmp=rander_whatsapp_variable($template_info['template_variable'],$whatsapp_variable_info,$client_info);
								$placeholders_arr=explode(",",$placeholders_tmp);
								$placeholders=implode(',', array_map(function($i){return '"'.$i.'"';}, $placeholders_arr));
							}			 				
							$headers  = [
								'accept: application/json',
								'apikey: '.$apikey,
								'Content-Type: application/json'
							];			
							$fields='{
								"from": "'.$from.'",
								"to": "'.$to.'",
								"type": "template",
								"message": {
									"templateid":"'.$templateid.'",
									"header": "'.$header.'",
									"placeholders": ['.$placeholders.'],
									"buttons": [{"index": 0,"type":"visit_website","placeholder":"whatsapp"}]}
							
							}';	
							$cURL = curl_init();
							curl_setopt($cURL, CURLOPT_HTTPHEADER,$headers);
							curl_setopt($cURL, CURLOPT_HEADER, true);
							curl_setopt($cURL, CURLOPT_URL, $url);
							curl_setopt($cURL, CURLOPT_POSTFIELDS,$fields);
							curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
							$response = curl_exec($cURL);
							$info = curl_getinfo($cURL);
							curl_close($cURL);									
							if($info['http_code']!=200)
							{
								$response_arr=explode('"',$response);					 
								$return_arr=array(
										'message_id'=>$response_arr[17],
										'message_type'=>'template',
										'body'=>'',
										'recipient'=>'',
										'error_code'=>'',
										'error_message'=>'',
										'http_code'=>$info['http_code']
										);
							}
							else
							{
								$response_arr=explode('"',$response);
								$return_arr=array(
										'message_id'=>'',
										'message_type'=>'',
										'body'=>'',
										'recipient'=>'',
										'error_code'=>$response_arr[3],
										'error_message'=>$response_arr[7],
										'http_code'=>$info['http_code']
										);
							}	
							return $return_arr;	
						}
					}					
				}
			}								
	    }
	}

	if ( ! function_exists('get_sync_email_account')) 
	{
		function get_sync_email_account()
		{
			$ci =& get_instance();
			$ci->load->model(array('Setting_model'));
			$all_session = $ci->session->userdata();
        	$logged_in_info = $all_session['admin_session_data'];
        	$user_id=$logged_in_info['user_id'];			
			$sync_email_account=$ci->Setting_model->get_gmail_for_sync($user_id);
			return $sync_email_account;
	    }
	}

	if ( !function_exists('get_latest_app') )
	{
		function get_latest_app()
	    {
	        $ci=& get_instance();
	        return 'lmsbaba_v005.apk';
	    }
	}

	if ( !function_exists('get_gmail_mails_decode') )
	{
		function get_gmail_mails_decode($mail_str)
	    {
	        $ci=& get_instance();
	        $returm_mail_str="";
			$return_mail_arr=array();
	        if($mail_str)
			{				
				$t_email_arr=explode(',', htmlentities(trim($mail_str)));
				if(count($t_email_arr))
				{
					foreach($t_email_arr AS $tm)
					{
						$tmp_str="";
						if (strpos(strip_tags(trim($tm)), '&lt;')) 
						{
						   $arr_tmp=explode("&lt;", trim($tm));				   
						   $tmp_str=str_replace("&gt;","",trim($arr_tmp[1]));				   
						}
						else
						{
							$tmp_str=trim($tm);
						}			
						$returm_mail_str .=trim($tmp_str).',';
						array_push($return_mail_arr, $tmp_str);	
					}
				}
			}
			return array('mail_str'=>rtrim($returm_mail_str,","),'mail_arr'=>$return_mail_arr);
	    }
	}

	if ( !function_exists('date_csv_format_to_db_format') )
	{
		function date_csv_format_to_db_format($date)
	    {
	        $ci=& get_instance();
	        if($date!='')
	        {
	        	return date("Y-m-d",strtotime(trim($date)));
				// $date_arr = explode('/',$date);
				// return $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
	        }
	        else
	        {
	        	return '';
	        }
			
	    }
	}

	if ( !function_exists('get_smtp') )
	{
		function get_smtp($client_info=array())
	    {
    		$ci=& get_instance();
			$ci->load->model(array('Setting_model'));
			$smtp_data=$ci->Setting_model->GetSmtpData($client_info);
			if(count($smtp_data)>0)
			{
				return $smtp_data;
			}	
			else
			{
				return $ci->Setting_model->GetDefaultSmtpData($client_info);
			}		
	    }
	}

	if ( !function_exists('get_invoice_no') )
	{
		function get_invoice_no($is_proforma_or_invoice)
	    {	
    		$ci=& get_instance();
			$ci->load->model(array('order_model'));
			// $pst = (date('Y')-1);
			// $pt = date('Y', strtotime('0 year'));
			// $arg=array();
			// $arg['pst']=$pst;
			// $arg['pt']=$pt;
			// $last_no=0;
			// if(strtoupper($is_proforma_or_invoice)=='P')
			// {
			// 	$last_no=$ci->order_model->GetLastProformaNo($arg) ;	
			// }
			// else if(strtoupper($is_proforma_or_invoice)=='I')
			// {
			// 	$last_no=$ci->order_model->GetLastInvoiceNo($arg) ;	
			// }
			// return $last_no;						
			$year_arr = [2020,2021,2022,2023,2024,2025,2026,2027,2028,2029,2030];
			$fn_month = 04;
			foreach ($year_arr as $year) 
			{
				$end_date = new DateTime($year . '-' . $fn_month . '-01');
				$start_date = clone $end_date;
				$start_date->modify('-1 year');
				$end_date->modify('-1 day');
				$date_range_arr[$year] = array('start_date' => $start_date->format('Y-m-d'), 
											'end_date' => $end_date->format('Y-m-d'));
			}
			$curr_date=date("Y-m-d");
			$fy_start_date=date("Y").'-04-01';
			if($curr_date>=$fy_start_date)
			{
				$fy= (date('Y')+1);
			}
			else
			{
				$fy = date('Y');
			}
			$curr_fy=$date_range_arr[$fy];
			$arg=array();
			$arg['fy_start_date']=$curr_fy['start_date'];
			$arg['fy_end_date']=$curr_fy['end_date'];
			if(strtoupper($is_proforma_or_invoice)=='P')
			{
				$last_no=$ci->order_model->GetLastProformaNo($arg) ;	
			}
			else if(strtoupper($is_proforma_or_invoice)=='I')
			{
				$last_no=$ci->order_model->GetLastInvoiceNo($arg) ;	
			}
			return $last_no;
	    }
	}

	if ( !function_exists('get_sync_call_number_filter') )
	{
		function get_sync_call_number_filter($string)
	    {
	        $ci=& get_instance();	        
			$string=str_replace(' ', '', $string); 
			$string=preg_replace('/[^A-Za-z0-9\-]/', '', $string);
			$string=preg_replace('/-+/', '', $string);
			$stringlen=strlen($string);
			$string_tmp=$string;
			if($stringlen!=10){
				if($stringlen<10){
					$string_tmp=$string;
				}
				else{
					$string_tmp=substr($string, -10);
				}
			}
			return $string_tmp;
	    }
	}

	if ( !function_exists('get_custom_stage') )
	{
		function get_custom_stage($client_info=array())
	    {	
    		$ci=& get_instance();
			$ci->load->model(array('Setting_model'));
			return $ci->Setting_model->get_custom_stage_info($client_info) ;			
	    }
	}

	if ( !function_exists('get_all_month') )
	{
		function get_all_month($set_no_of_month=12)
	    {
			$ci=& get_instance();			
			$months[1]='January';
			$months[2]='February';
			$months[3]='March';
			$months[4]='April';
			$months[5]='May';
			$months[6]='June';
			$months[7]='July';
			$months[8]='August';
			$months[9]='September';
			$months[10]='October';
			$months[11]='November';
			$months[12]='December';
			return $months;			
			
	    }
	}

	if ( !function_exists('get_template_variable_list') )
	{
		function get_template_variable_list($client_info=array())
	    {
    		$ci=& get_instance();
			$ci->load->model(array('Setting_model'));
			return $ci->Setting_model->get_template_variable_list($client_info) ;			
	    }
	}

	// --------------------------------------------------------------
	// SMS API SCRIIPT
	if ( !function_exists('rander_sms_template') )
	{
		function rander_sms_template($message='',$sms_variable_info=array(),$client_info=array())
	    {
    		$ci=& get_instance();
			$ci->load->model(array('Setting_model','customer_model','lead_model','user_model'));
			$get_template_variable_list=get_template_variable_list($client_info);
			
			if(count($get_template_variable_list)>0 && $message!='' && count($sms_variable_info)>0)
			{
				foreach($get_template_variable_list AS $variable)
				{
					if (strpos($message, $variable['reserve_keyword']) !== false) {
						if($variable['variable_type']=='buyer_details' && $sms_variable_info['customer_id']!='')
						{
							$customer_info=$ci->customer_model->sms_customer_row($sms_variable_info['customer_id'],$client_info);
							if(count($customer_info))
							{
								$replaced_str=$customer_info[trim($variable['reserve_keyword'],'#')];
								if($replaced_str)
								{
									$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
								}
							}							
						}

						if($variable['variable_type']=='company' && $sms_variable_info['company_id']!='')
						{
							$company_info=$ci->Setting_model->sms_company_row($client_info);
							if(count($company_info))
							{
								$replaced_str=$company_info[trim($variable['reserve_keyword'],'#')];
								if($replaced_str)
								{
									$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
								}
							}							
						}

						if($variable['variable_type']=='lead_details' && $sms_variable_info['lead_id']!='')
						{
							$lead_info=$ci->lead_model->sms_lead_row($sms_variable_info['lead_id'],$client_info);
							if(count($lead_info))
							{
								$replaced_str=$lead_info[trim($variable['reserve_keyword'],'#')];
								if($replaced_str)
								{
									$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
								}
							}							
						}

						if($variable['variable_type']=='user_details' && $sms_variable_info['user_id']!='')
						{
							$user_info=$ci->user_model->sms_user_row($sms_variable_info['user_id'],$client_info);
							if(count($user_info))
							{
								$replaced_str=$user_info[trim($variable['reserve_keyword'],'#')];
								if($replaced_str)
								{
									$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
								}
							}							
							
						}						
					}
				}
			}
			return $message;		
	    }
	}

	if ( !function_exists('sms_send') )
	{
		function sms_send($sms_send_data=array(),$sms_variable_info=array(),$client_info=array())
		{
			$ci=& get_instance();
			$ci->load->model(array('Sms_setting_model','customer_model','lead_model','user_model'));
			
			$return_arr=array();
			foreach($sms_send_data AS $sms_row)
			{
				if($sms_row['mobile']!='' && $sms_row['template_auto_id']!='')
				{
					$template_info=$ci->Sms_setting_model->GetTemplateDetails($sms_row['template_auto_id'],$client_info);	
					if(count($template_info))
					{
						if($template_info['api_sms_service_provider_id']=='1') //Pinnacle Teleservices Pvt. Ltd.
						{
							$mobileno=$sms_row['mobile'];
							$message=rander_sms_template($template_info['text'],$sms_variable_info,$client_info);
							$smsGatewayUrl = 'http://api.pinnacle.in/index.php/sms/send/';	
							$sender=$template_info['api_sender'];	
							$APIKey=$template_info['api_apikey'];
							$EntityID=$template_info['api_entity_id'];
							$tempid=$template_info['template_id'];
							$textmessage=urlencode($message);		
							$api_element=$sender.'/'.$mobileno.'/'.$textmessage.'/TXT';
							$api_params=$api_element.'?apikey='.$APIKey.'&dltentityid='.$EntityID.'&dlttempid='.$tempid.'';
							$smsgatewaydata=$smsGatewayUrl.$api_params;
							$output=file_get_contents($smsgatewaydata);					
							array_push($return_arr,$output);
						}
					}
				}
			}
			return $return_arr;
		}
	}
	// SMS API SCRIIPT
	// --------------------------------------------------------------
	
	if ( !function_exists('distance_by_lat_long') )
	{
		function distance_by_lat_long($lat1, $lon1, $lat2, $lon2, $unit)
	    {
	        $ci=& get_instance();

	        $theta = $lon1 - $lon2;
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$unit = strtoupper($unit);
		
			if ($unit == "K") {
				return ($miles * 1.609344);
			} else if ($unit == "N") {
				return ($miles * 0.8684);
			} else {
				return $miles;
			}			
	    }
	}


	if ( !function_exists('get_user_wise_kpi_count') )
    {
    	function get_user_wise_kpi_count($user_id,$kpi_id,$y_month='',$client_info=array())
	    {     
	    	$ci=& get_instance();
	    	$ci->load->database();
			$ci->fsas_db = $ci->load->database('default', TRUE); 
			$controller = $ci->router->fetch_class(); // class = controller
			if($controller=='cronjobs')
			{
				$config['hostname'] = DB_HOSTNAME;
		        $config['username'] = $client_info->db_username;
		        $config['password'] = $client_info->db_password;
		        $config['database'] = $client_info->db_name;
		        $config['dbdriver'] = 'mysqli';
		        $config['dbprefix'] = '';
		        $config['pconnect'] = FALSE;
		        $config['db_debug'] = TRUE;
		        $config['cache_on'] = FALSE;
		        $config['cachedir'] = '';
		        $config['char_set'] = 'utf8';
		        $config['dbcollat'] = 'utf8_general_ci'; 
		        $ci->client_db=$ci->load->database($config,TRUE);
			}
			else
			{
				$ci->client_db = $ci->load->database('client', TRUE);
			}	  

			$subsql="";
			if($y_month)
			{
				$subsql .="";
			}
			else
			{
				$curr_y_month=date('Y-m', strtotime(date("Y-m-d")));
				$subsql .=" AND DATE_FORMAT(datetime,'%Y-%m')='".$curr_y_month."'";
			}		

	        $sql="SELECT id FROM kpi_log WHERE kpi_id=$kpi_id AND user_id=$user_id $subsql";
			$query = $ci->client_db->query($sql);
			if($query){
				return $query->num_rows();
			}
			else{
				return 0;
			}
			
	    }
    }

	if ( !function_exists('create_kpi_log') )
    {
    	function create_kpi_log($kpi_id,$user_id,$value='',$datetime='',$client_info=array())
	    {     
	    	$ci=& get_instance();	
			$ci->load->model(array('My_performance_model'));
			$post_data=array(
							'kpi_id'=>$kpi_id,
							'user_id'=>$user_id,
							'value'=>$value,
							'datetime'=>$datetime
						);
			return $ci->My_performance_model->create_kpi_log($post_data,$client_info) ;			
	    }
    }	

	if ( !function_exists('get_menu_label_alias') )
	{
		function get_menu_label_alias()
	    {
	        $ci=& get_instance();
	        return $ci->config->item('menu_label_alias');
	    }
	}

	if ( !function_exists('get_non_menu_disable_ids') )
	{
		function get_non_menu_disable_ids()
	    {
	        $ci=& get_instance();
	        return $ci->config->item('non_menu_disable_ids');
	    }
	}

	if ( !function_exists('get_tradeindia_email_exclude') )
	{
		function get_tradeindia_email_exclude()
	    {
	        $ci=& get_instance();
	        return $ci->config->item('get_tradeindia_email_exclude');
	    }
	}

	if ( !function_exists('tata_call_by_c2c') )
	{
		function tata_call_by_c2c($arrgument,$client_info=array())
	    {
	        $ci=& get_instance();				
			
			$return_arr=array();			
			$url=$arrgument['url'];			
			$agent_number=$arrgument['agent_number'];
			$destination_number=$arrgument['destination_number'];
			$token=$arrgument['authorization_token'];						 				
			$headers  = [
				'accept: application/json',
				'Authorization: '.$token,
				'Content-Type: application/json'
			];			
			$fields='{
				"agent_number": "'.$agent_number.'",
				"destination_number": "'.$destination_number.'"
			}';	
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_HTTPHEADER,$headers);
			curl_setopt($cURL, CURLOPT_HEADER, true);
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POSTFIELDS,$fields);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($cURL);
			$info = curl_getinfo($cURL);
			curl_close($cURL);	
											
			if($info['http_code']!=200)
			{
				$response_arr=explode('"',$response);					 
				$return_arr=array(
						'url'=>$info['url'],
						'message'=>$response_arr[5],
						'http_code'=>$info['http_code']
						);
			}
			else
			{
				$response_arr=explode('"',$response);
				$return_arr=array(
						'url'=>$info['url'],
						'message'=>$response_arr[5],
						'http_code'=>$info['http_code']
						);
			}	
			return $return_arr;									
	    }
	}

	if ( !function_exists('is_permission_available') )
    {
    	function is_permission_available($reserve_key)
	    { 			
	    	$ci=& get_instance();
	    	$ci->load->database(); 
	    	$ci->client_db = $ci->load->database('client', TRUE);			
	    	$all_session = $ci->session->userdata(); 
        	$logged_in_info = $all_session['admin_session_data'];
			$user_wise_permission=$logged_in_info['user_wise_permission'];
			if (!empty($user_wise_permission) && in_array($reserve_key, $user_wise_permission))
			{
				if(in_array($reserve_key,$user_wise_permission)){
					return TRUE;
				}
				else{
					return FALSE;
				}	
			}
			else{
				return FALSE;
			}
								
			// $sql="SELECT id FROM tbl_user_wise_permission WHERE user_id='".$logged_in_info['user_id']."' AND reserved_keyword='".$reserve_key."'";			
			// $query = $ci->client_db->query($sql);
			// if($query){
			// 	if($query->num_rows()==0){	
			// 		return FALSE;				
			// 	}
			// 	else{
			// 		return TRUE;
			// 	}			
			// }
			// else{
			// 	return FALSE;
			// }
	    }
    }

	if ( !function_exists('is_admin_element_permission_available') )
    {
    	function is_admin_element_permission_available($menu_id,$reserve_key)
	    {     
	    	$ci=& get_instance();

			$admin_session_data = $ci->session->userdata('adminportal_session_data'); 
	        $user_id = $admin_session_data['user_id'];
			$user_type = $admin_session_data['user_type'];
			if($user_type=="Admin"){
				return TRUE;
			} else {

				$ci->load->database();
				$ci->fsas_db = $ci->load->database('default', TRUE); 

				$sql="
				SELECT 
				T1.id,
				T2.element_id
				FROM tbl_admin_menu_element AS T1
				INNER JOIN tbl_user_wise_admin_menu_element_permission AS T2 ON T1.id=T2.element_id
				WHERE T1.menu_id='".$menu_id."' AND T1.reserved_keyword='".$reserve_key."' AND T2.user_id='".$user_id."'
				
				";			
				$query = $ci->fsas_db->query($sql);
				if($query){
					if($query->num_rows()==0){	
						return FALSE;				
					}
					else{
						return TRUE;
					}			
				}
				else{
					return FALSE;
				}
			}


	    }
    }

	// ==========================================================
	// ADMIN PORTER /ADMINMASTER
	if ( !function_exists('adminportal_url') )
	{
		function adminportal_url()
	    {
	        $ci=& get_instance();
	        return $ci->config->item('adminportal_url');
	    }
	}
	if ( !function_exists('is_adminportal_logged_in') )
	{
		function is_adminportal_logged_in()
	    {
	    	$ci=& get_instance();	    	
    		$admin_session_data = $ci->session->userdata('adminportal_session_data'); 
	        $is_admin_logged_in = $admin_session_data['is_adminportal_logged_in'];
	        if(!isset($is_admin_logged_in) || $is_admin_logged_in == FALSE)
	        {	
	        	redirect(adminportal_url().'login'); 	            
	        }
	    }
	}

	if ( !function_exists('chk_access_menu_permission') )
	{
		function chk_access_menu_permission($menu_id)
	    {
	    	$ci=& get_instance();	    	
    		$admin_session_data = $ci->session->userdata('adminportal_session_data'); 
	        $user_type = $admin_session_data['user_type'];
			$menu_permission_id = $admin_session_data['menu_permission_id'];
	        if(!isset($user_type))
	        {	
	        	redirect(adminportal_url().'login'); 	            
	        } 
			elseif($user_type != "Admin" && in_array($menu_id,$menu_permission_id)==FALSE)
			{
				redirect(adminportal_url().'dashboard');
			}
	    }
	}

	if ( !function_exists('get_permission_wise_menu_list') )
	{
		function get_permission_wise_menu_list()
	    {
	    	$ci=& get_instance();	    	

			$admin_session_data = $ci->session->userdata('adminportal_session_data');
			if($admin_session_data['user_type']=="Admin"){
				return $ci->Adminportal_model->get_all_menu_list();
			} else {
				$menu_permission_id = implode(',',$admin_session_data['menu_permission_id']);
				return $ci->Adminportal_model->get_user_wise_menu_list($menu_permission_id);
			}

	    }
	}

	if ( !function_exists('init_adminportal_element') )
	{
		function init_adminportal_element()
	    {
	    	init_adminportal_head();
	    }
	}

	if ( !function_exists('init_adminportal_head') )
	{
		function init_adminportal_head()
	    {
    		$ci=& get_instance();
	    	$data = array();
	    	$controller = $ci->router->fetch_class(); // class = controller
			$method = $ci->router->fetch_method();
			$data['controller'] = $controller;
			$data['method'] = $method;				
			// SET DATABASE DEFINE
			$data['page_title'] 		= "";
			$data['page_keyword'] 		= "";
			$data['page_description'] 	= "";			
			$ci->load->view('adminmaster/includes/head',$data,true);
	    }
	}
	// ADMIN PORTER / ADMINMASTER
	// ==========================================================

	if ( !function_exists('ip_info') )
	{
		function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) 
		{
			$output = NULL;
			if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
				$ip = $_SERVER["REMOTE_ADDR"];
				if ($deep_detect) {
					if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
						$ip = $_SERVER['HTTP_CLIENT_IP'];
				}
			}
			$purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
			$support    = array("country", "countrycode", "state", "region", "city", "location", "address");
			$continents = array(
				"AF" => "Africa",
				"AN" => "Antarctica",
				"AS" => "Asia",
				"EU" => "Europe",
				"OC" => "Australia (Oceania)",
				"NA" => "North America",
				"SA" => "South America"
			);
			if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
				$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
				if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
					switch ($purpose) {
						case "location":
							$output = array(
								"city"           => @$ipdat->geoplugin_city,
								"state"          => @$ipdat->geoplugin_regionName,
								"country"        => @$ipdat->geoplugin_countryName,
								"country_code"   => @$ipdat->geoplugin_countryCode,
								"continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
								"continent_code" => @$ipdat->geoplugin_continentCode
							);
							break;
						case "address":
							$address = array($ipdat->geoplugin_countryName);
							if (@strlen($ipdat->geoplugin_regionName) >= 1)
								$address[] = $ipdat->geoplugin_regionName;
							if (@strlen($ipdat->geoplugin_city) >= 1)
								$address[] = $ipdat->geoplugin_city;
							$output = implode(", ", array_reverse($address));
							break;
						case "city":
							$output = @$ipdat->geoplugin_city;
							break;
						case "state":
							$output = @$ipdat->geoplugin_regionName;
							break;
						case "region":
							$output = @$ipdat->geoplugin_regionName;
							break;
						case "country":
							$output = @$ipdat->geoplugin_countryName;
							break;
						case "countrycode":
							$output = @$ipdat->geoplugin_countryCode;
							break;
					}
				}
			}
			return $output;
		}
	}

	if ( !function_exists('get_form_fields_type_full_text') )
    {
    	function get_form_fields_type_full_text($input_type_reserve_key)
	    {     
	    	$ci=& get_instance();	
			$ff='';
			if($input_type_reserve_key=='IB'){
				$ff='Input Box';
			}
			else if($input_type_reserve_key=='TA'){
				$ff='Text Area';
			}
			else if($input_type_reserve_key=='D'){
				$ff='Date';
			}
			else if($input_type_reserve_key=='DT'){
				$ff='Date & Time';
			}
			else if($input_type_reserve_key=='R'){
				$ff='Radio Button';
			}
			else if($input_type_reserve_key=='CB'){
				$ff='Checkbox';
			}
			else if($input_type_reserve_key=='S'){
				$ff='Select Option';
			}
			else if($input_type_reserve_key=='F'){
				$ff='File';
			}			
			return $ff;
	    }
    }	
	

	if ( !function_exists('get_om_priority_name_by_id') )
    {
    	function get_om_priority_name_by_id($id)
	    {     
	    	$ci=& get_instance();	
			$p='';
			if($id=='1'){
				$p='Normal';
			}
			else if($id=='2'){
				$p='High';
			}			
			return $p;
	    }
    }	

	if ( !function_exists('remove_html_tags') )
    {
    	function remove_html_tags($html='',$strip_tags="a|strong|em|h1|h2|h3|h4|h5|h6")
	    {     
	    	$ci=& get_instance();	
			if($html){
				$clean_html = preg_replace("#<\s*\/?(".$strip_tags.")\s*[^>]*?>#im", '', $html);			
				return $clean_html;
			}	
			else{
				return '';
			}
	    }
    }

	if ( !function_exists('is_om_link_available') )
    {
    	function is_om_link_available($reserve_key)
	    {     
	    	$ci=& get_instance();
	    	$ci->load->database(); 
	    	$ci->client_db = $ci->load->database('client', TRUE);			
	    	$all_session = $ci->session->userdata();
        	$logged_in_info = $all_session['admin_session_data'];  
			
			$sql="SELECT t1.id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='".$logged_in_info['user_id']."' AND t1.link_keyword='".$reserve_key."'";
			$query = $ci->client_db->query($sql);	
			if($query){
				if($query->num_rows()>0)
				{	
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}	
			else{
				return FALSE;
			}        	
        	// if($logged_in_info['user_id']==1)
        	// {
        	// 	return TRUE;        		
        	// }
        	// else
        	// {         		
        	// 	$sql="SELECT id FROM om_permission_link AS t1 
			// 	INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
			// 	WHERE t2.user_id='".$logged_in_info['user_id']."' AND t1.link_keyword='".$reserve_key."'";
			// 	$query = $ci->client_db->query($sql);	
			// 	if($query){
			// 		if($query->num_rows()>0)
			// 		{	
			// 			return TRUE;
			// 		}
			// 		else
			// 		{
			// 			return FALSE;
			// 		}
			// 	}	
			// 	else{
			// 		return FALSE;
			// 	}
        	// }
	    }
    }
?>