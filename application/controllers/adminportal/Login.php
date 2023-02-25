<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	public function __construct()
	{	
		parent::__construct();
		init_adminportal_element();	
		$this->load->model(array('client_model','Adminportal_model'));	
	}
	public function index()
	{		
		$data=array();
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			if($this->validate_form_data() == TRUE)
			{
				$username = $this->input->post('adminportal_username');
				$password = $this->input->post('adminportal_password');
				$chk_valid_admin_login=$this->client_model->chk_valid_adminportal_login($username,md5($password));
				$chk_valid_admin_login=$this->client_model->chk_valid_adminportal_login($username,md5($password));
				$menu_permission_id=array();
				if($chk_valid_admin_login==false)
				{
					$msg='Oops! Username/ password invalid.';
		            $this->session->set_flashdata('error_msg', $msg);
		            redirect(adminportal_url().'login', 'refresh');
				} else {
					$argument['user_id']=$chk_valid_admin_login->id;
					$menu_permission_id=$this->Adminportal_model->get_menu_permission_id_list($argument);
				}
				$sessData = array(
								'user_id'=>$chk_valid_admin_login->id,
								'user_type'=>$chk_valid_admin_login->user_type,								
								'user_name'=>$chk_valid_admin_login->name,									
								'user_email'=>$chk_valid_admin_login->email,
								'user_mobile'=>$chk_valid_admin_login->mobile,
								'is_adminportal_logged_in'=>TRUE,
								'menu_permission_id'=>$menu_permission_id
							  );
							
				//Set User Session
				$this->session->set_userdata('adminportal_session_data',$sessData);
				redirect(adminportal_url().'client');				
			}
			else
			{
				//$this->form_validation->set_error_delimiters('', '');
	        	//$msg = validation_errors(); // 'duplicate';
	        	$msg='All fields are required.';
	            $this->session->set_flashdata('error_msg', $msg);
	            redirect(adminportal_url().'login', 'refresh');
			}
		}
		$this->load->view('adminmaster/login_view', $data);
	}

	// --------------------------------------------------------------------------
	// LOGIN VALIDATION FUNCTION 
	function validate_form_data()
	{ 
		$this->load->library('form_validation');
		$this->form_validation->set_rules('adminportal_username', 'Username/ Email', 'trim|required');
		$this->form_validation->set_rules('adminportal_password', 'password', 'required');

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


	function checkAccessPermission()
    {       
        $data=array();
        $data["class"]      = $this->router->fetch_class();
        $data["method"]     = $this->router->fetch_method();
        $this->accessable_function_arr  = array();
        $all_session = $this->session->userdata();
        $logged_in_info = $all_session['admin_session_data'];
        
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
    }

    
}