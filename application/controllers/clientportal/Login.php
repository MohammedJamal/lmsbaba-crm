<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	private $api_access_token = '';
	//protected $accessable_function_arr = array();
    //protected $class_name='';	
	public function __construct()
	{	
		parent::__construct();
		init_admin_login_element();
		$this->api_access_token = get_api_access_token();	
		$this->load->model(array('user_model','client_model','Setting_model'));
		//$this->class_name=$this->router->fetch_class();	
		//$accessable_function_arr = array();	
	}
	public function index()
	{		
		$data=array();	
		$data['id']=$this->uri->segment(3);
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$token = $this->input->post('id');
			$id = $this->client_model->get_id_from_token($token);
			if($this->validate_form_data() == TRUE)
			{				
				$remember = $this->input->post('remember');
				$login_id = $this->input->post('login_id');
				$login_password = $this->input->post('login_password');
				$chk_valid_client=$this->client_model->chk_valid_client($id);
				if($chk_valid_client==false)
				{
					$msg='Client information not available, Please contact your LMSBaba servicing manager.';
		            $this->session->set_flashdata('error_msg', $msg);
		            redirect(admin_url().'login/'.$token, 'refresh');
				}

				$chk_valid_admin_login=$this->user_model->chk_valid_admin_login($login_id,md5($login_password));
				if($chk_valid_admin_login==false)
				{
					$msg='User ID / password invalid.';
		            $this->session->set_flashdata('error_msg', $msg);
		            redirect(admin_url().'login/'.$token, 'refresh');
				}

				$client_info=$this->client_model->get_details($id);
				$admin_user_info=$this->user_model->get_admin_user_details($chk_valid_admin_login);

				$service_info=$this->checkAccessPermission($id,$admin_user_info->id);				
				if($service_info['is_service_tagged']=='N' || $service_info['is_all_service_expire']!='N')
				{
					if($service_info['is_service_tagged']=='N')
					{
						$msg='No service tagged to you. Please contact your Admin.';
					}
					else if($service_info['is_all_service_expire']!='N')
					{
						$msg='No active service found! Please contact your LMSBaba servicing manager.';
					}					
		            $this->session->set_flashdata('error_msg', $msg);
		            redirect(admin_url().'login/'.$token, 'refresh');
				}				

				$user_wise_permission=$this->user_model->get_user_wise_permission($admin_user_info->id);				
				$sessData = array(
								'id'=>$client_info->api_access_token,
								'client_id'=>$client_info->id,
								'company_name'=>$client_info->name,
								'client_info'=>$client_info,
								'user_id'=>$admin_user_info->id,
								'user_name'=>$admin_user_info->name,
								'user_type'=>$admin_user_info->user_type,
								'email'=>$admin_user_info->email,
								'name'=>$admin_user_info->name,     
								'personal_photo'=>$admin_user_info->photo,
								'photo'=>'',         
								'company_name'=>$admin_user_info->company_name,	
								'mobile'=>$admin_user_info->mobile,	
								'user_type'=>$admin_user_info->user_type,
								'lms_url'=>'clientportal',
								'session_id'=>time(),
								'is_admin_logged_in'=>TRUE,	
								'is_logged_in' => TRUE,
								'service_info'=>$service_info,
								'user_wise_permission'=>$user_wise_permission
							  );				
				//Set User Session
				$this->session->set_userdata('admin_session_data',$sessData);

				// set company info
				$company_info=$this->Setting_model->GetCompanyData($client_info) ;
				$this->session->set_userdata('company_info',$company_info);


				// $this->checkAccessPermission();

				// =====================================
				// CREATE FILE
				// $cookie_name = "lmsbaba_admin_user";
				// $cookie_value = time();
				// setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

				// $myfile = fopen("assets/uploads/clients/log/".$cookie_value."_client_id.txt", "w") or die("Unable to open file!");	
				// fwrite($myfile, trim($client_info->api_access_token));				
				// fclose($myfile);

				// CREATE FILE
				// =====================================

				/* ==================================================== */
				/* COOKIE CREATE / DELETE FOR REMEMBER ME FUNCTIONALITY */
				// $this->load->helper('cookie');
	            // $cookie_prefix = $this->config->item('cookie_prefix');
	            // $cookie_domain = $this->config->item('cookie_domain');
	            // $cookie_path   = $this->config->item('cookie_path');

	            // if($remember=="Y") //create cookie
	            // {
                //     $cookie = array(
                //             'name'   => 'lmsbabaSaasUserAuth',
                //             'value'  => base64_encode($login_id).'~'.base64_encode($login_password),
                //             'expire' => time()+86500,
                //             'domain' => $cookie_domain,
                //             'path'   => $cookie_path,
                //             'prefix' => $cookie_prefix,
                //             );
                //     // print_r($cookie); die();
                //     set_cookie($cookie);
	            // }
	            // else // delete cookie
	            // {
	            //     $cookie = array(
	            //                 'name'   => 'lmsbabaSaasUserAuth',
	            //                 'value'  => "",
	            //                 'expire' => 0,
	            //                 'domain' => $cookie_domain,
	            //                 'path'   => $cookie_path,
	            //                 'prefix' => $cookie_prefix,
	            //                 );

	            //     delete_cookie($cookie);
	            // }
	            /* COOKIE CREATE / DELETE FOR REMEMBER ME FUNCTIONALITY */
	            /* ========================================== */
            	
            	// =========================
            	// logged in history
				$ip_address=$this->input->ip_address();
            	$get_last_login_h=$this->user_model->last_user_login_history($admin_user_info->id);
            	// if(count($get_last_login_h))
				if(isset($get_last_login_h['action_type']))
            	{
            		if($get_last_login_h['action_type']=='LI')
            		{
            			$data_post=array();
            			$data_post=array(
						'user_id'=>$admin_user_info->id,
						'action_at'=>date("Y-m-d H:i:s"),
						'action_type'=>'LO',
						'ip_address'=>$ip_address
		            	);
		            	$this->user_model->add_user_login_history($data_post);
            		}
            	} 
				
				if($company_info['is_session_expire_for_idle']=='Y')
				{
					$data_post=array();          	
					$data_post=array(
					'user_id'=>$admin_user_info->id,
					'action_at'=>date("Y-m-d H:i:s"),
					'action_type'=>'LI',
					'ip_address'=>$ip_address
					);
					$this->user_model->add_user_login_history($data_post);
				}            	
            	// =========================

				// ===========================
				// Update last login datetime
				$last_login_post=array(
					'last_login_datetime'=>date("Y-m-d H:i:s")
					);
					$this->user_model->UpdateAdmin($last_login_post,$admin_user_info->id);
				// Update last login datetime
				// =============================
				redirect(admin_url().'dashboard');				
			}
			else
			{
				//$this->form_validation->set_error_delimiters('', '');
	        	$msg = validation_errors(); // 'duplicate';
	        	// $msg='All fields are required.';
	            $this->session->set_flashdata('error_msg', $msg);
	            redirect(admin_url().'login/'.$token, 'refresh');
			}
		}		

		// =====================================
		// GET COOKIE FOR REMEMBER USERNAME
		// $this->load->helper('cookie');
        // $cookie_prefix 			= $this->config->item('cookie_prefix');
        // $cookie_name   			= $cookie_prefix."lmsbabaSaasUserAuth";
        // $adminAuth     			= get_cookie($cookie_name);

        // $data["cookie_username"] = '';
        // $data["cookie_password"] = '';
        // $data["is_cookie_set"] = 'N';
        // if($adminAuth)
        // {
        //     $admin_auth_arr=explode('~', $adminAuth);
        //     if(count($admin_auth_arr)==2)
        //     {
        //     	$data["cookie_username"] = base64_decode($admin_auth_arr[0]);
        //     	$data["cookie_password"] = base64_decode($admin_auth_arr[1]);
        //     	$data["is_cookie_set"] = 'Y';
        //     } 
        // } 
        // END
        // =====================================
		$this->load->view('admin/login_view', $data);
	}

	// --------------------------------------------------------------------------
	// LOGIN VALIDATION FUNCTION 
	function validate_form_data()
	{ 
		$this->load->library('form_validation');
		$this->form_validation->set_rules('login_id', 'Login ID', 'trim|required');
		$this->form_validation->set_rules('login_password', 'password', 'required');
		$this->form_validation->set_rules('chk_terms_of_services', 'By continuing, you agree to Lmababa\'s Terms of Services and Privacy Policy', 'required');

		if ($this->form_validation->run() == TRUE)
		{
		  return TRUE;
		}
		else
		{
		  return FALSE;
		}
	}
	// LOGIN VALIDATION FUNCTION 
	// ----------------------------------------------------------------------------


	function checkAccessPermission($client_id='',$user_id='')
    { 
		// die($client_id.'/'.$user_id);
		$is_all_service_expire='U';
		$return=array();
		$service_tagged=array();
		$service_order_detail_tagged=array();
		$get_service_order=array();
		$get_service_order_detail=array();
		$get_user_tagged_service_order=$this->user_model->user_tagged_service_order($user_id);
		
		if(count($get_user_tagged_service_order)>0)
		{
			$is_service_tagged='Y';	
			$get_service_order=$this->client_model->get_service_order_by_client($client_id);
			$get_service_order_detail=$this->client_model->get_client_wise_service_order_list($client_id);	
			if(count($get_service_order_detail))
			{
				foreach($get_service_order_detail AS $row)
				{
					if(in_array($row['id'],$get_user_tagged_service_order))
					{
						array_push($service_tagged,$row['service_id']);
						$service_order_detail_tagged[]=$row;
					}
				}

				// expiry check
				if(count($service_order_detail_tagged))
				{
					$is_all_service_expire='Y';
					foreach($service_order_detail_tagged AS $tagged)
					{
						if($tagged['expiry_date']>=date("Y-m-d"))
						{
							$is_all_service_expire='N';
							break;
						}
					}
				}
			}							
		}
		else
		{
			$is_service_tagged='N';
		}

		if($is_service_tagged=='Y' && $is_all_service_expire=='N')
		{
			$data["service_wise_menu"]=$this->user_model->get_service_wise_menu();
			$this->session->set_userdata('service_wise_menu',$data["service_wise_menu"]);
		}
		$return=array(
					'is_service_tagged'=>$is_service_tagged,
					'all_available_service'=>$get_service_order,
					'all_available_service_order'=>$get_service_order_detail,
					'service_tagged'=>$service_tagged,
					'service_order_detail_tagged'=>$service_order_detail_tagged,
					'is_all_service_expire'=>$is_all_service_expire,
					);
		return $return;
		
		/*
		$data=array();
		$data["class"]      = $this->router->fetch_class();
        $data["method"]     = $this->router->fetch_method();
        $this->accessable_function_arr  = array();
        $all_session = $this->session->userdata();
        $logged_in_info = $all_session['admin_session_data'];   
		$data["service_wise_menu"] = $this->user_model->get_service_wise_menu($data);
		$this->session->set_userdata('service_wise_menu',$data["service_wise_menu"]);
        $data['package_data'] = $this->user_model->get_package($data); 
        if($data['package_data']!='package_expire')
        {   
            if($data['package_data']!='')
            {
                $package_id = $data['package_data']['all'][0]['package_id'];
                $package_order_id = $data['package_data']['all'][0]['package_order_id'];
           

                if($logged_in_info['user_id'] == 1)
                {
                    // ----------------------------------------------------
                    $available_ids = array(); 
                    $available_sub_menu_id_str = '';  
                    if(count($data['package_data']['menu']))
                    {
                        foreach($data['package_data']['menu'] as $menu)
                        {
                            $available_sub_menu_id_str.=$menu['sub_menu_ids'].',';
                            array_push($available_ids, $menu['menu_id']);
                        }
                    }
                    
                    $available_sub_menu_id_str = rtrim($available_sub_menu_id_str,",");
                    
                    $data['available_sub_menu_id_str'] = $available_sub_menu_id_str;
                    $data['available_menu_id_str'] = implode(",",$available_ids);
                    // -----------------------------------------------------

                    $get_user_access_data = $data['package_data'];
                }
                else
                {
                   
                    $get_user_access_data =  $this->user_model->get_user_permission($logged_in_info['user_id']);

                    // ----------------------------------------------------
                    $available_ids = array();  
                    if($get_user_access_data['menu'])
                    {
                        foreach($get_user_access_data['menu'] as $menu)
                        {
                            array_push($available_ids, $menu['menu_id']);
                        }
                    }
                    
                    $data['available_menu_id_str'] = implode(",",$available_ids);
                    // -----------------------------------------------------
                }

                if($data['available_menu_id_str']!=''){
                	
                    $data["menu"] = $this->user_model->get_menu($data);					
                }
                else{
                    $data["menu"]     = array();
                }   
				
                //$this->CI->package_id=$package_id;
		        //$this->CI->package_order_id=$package_order_id;
		        //$this->CI->package_data=$data['package_data'];
				// print_r($data["service_wise_menu"]); die();
		        //Set User Session
		        $this->session->set_userdata('package_id',$package_id);
		        $this->session->set_userdata('package_order_id',$package_order_id);
		        $this->session->set_userdata('package_data',$data['package_data']);
		        $this->session->set_userdata('permission_data',$get_user_access_data);
		        $this->session->set_userdata('menu',$data["menu"]);
				
		        //$this->permission_data=$get_user_access_data;
		        //$this->menu=$data["menu"];            
		        return true;              
            }
            else
            {
                $package_id = '';
                $package_order_id = '';
                $data["menu"] = array();
                redirect(admin_url().'logout');
                //die("Package data not found!");
            }             
        }
        else
        {
            $package_id = '';
            $package_order_id = '';
            $data["menu"] = array();
            redirect(admin_url().'logout');
            //die("Package has been expired!");
        }    
		*/         
    }

	

    
}