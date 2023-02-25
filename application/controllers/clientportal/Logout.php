<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
		init_admin_element();
		//is_admin_logged_in();
		$this->api_access_token = get_api_access_token();	
		$this->load->model(array('user_model','client_model'));		
	}
	public function index()
	{		
		// -------------------------------------
		$admin_session_data=$this->session->userdata('admin_session_data');
		$user_id=$admin_session_data['user_id'];
		$client_id=$admin_session_data['client_id'];
		$token=$admin_session_data['client_info']->api_access_token;
		
		$session_array = array(
							   'id'=>'',
								'client_id'=>'',
								'company_name'=>'',
								'client_info'=>'',
								'user_id'=>'',
								'user_name'=>'',
								'user_type'=>'',
								'email'=>'',
								'name'=>'',     
								'personal_photo'=>'',
								'photo'=>'',         
								'company_name'=>'',	
								'mobile'=>'',	
								'user_type'=>'',
								'lms_url'=>'',
								'session_id'=>'',
								'is_admin_logged_in'=>FALSE,	
								'is_logged_in' => FALSE,
								'service_info'=>'',
								'user_wise_permission'=>''
							  );
						
		$this->session->unset_userdata('admin_session_data',$session_array);
		// -------------------------------------


		// -------------------------------------
		$session_lead_search_array = array(
							   'assigned_user'=>'',
							   'opportunity_stage'=>'',
							   'opportunity_status'=>'',
							   'lead_to_date'=>'',
							   'lead_from_date'=>'',
							   'search_keyword'=>'',
							  );						
		//$this->session->unset_userdata('lead_search',$session_lead_search_array);
		// -------------------------------------

		// -------------------------------------
		//Un-Set User Session
        // $this->session->unset_userdata('package_id','');
        // $this->session->unset_userdata('package_order_id','');
        // $this->session->unset_userdata('package_data','');
        // $this->session->unset_userdata('permission_data','');
        // $this->session->unset_userdata('menu','');

        $this->session->unset_userdata('service_wise_menu','');
        // -------------------------------------



		if(isset($_COOKIE['lmsbaba_admin_user'])) 
    	{
		    $tmp_client_id=$_COOKIE['lmsbaba_admin_user'];							    
		    @unlink("assets/uploads/clients/log/".$tmp_client_id."_client_id.txt");	
		}

		$msg = 'You have successfully logged out.';
		$this->session->set_flashdata('success_msg', $msg);

		// =========================
    	// logged in history 
		$ip_address=$this->input->ip_address();
    	$get_last_login_h=$this->user_model->last_user_login_history($user_id);
    	if(count($get_last_login_h))
    	{
    		// if($get_last_login_h['action_type']=='LO')
			// {
    		// 	$data_post=array(
			// 		'user_id'=>$user_id,
			// 		'action_at'=>date("Y-m-d H:i:s"),
			// 		'action_type'=>'LI',
			// 		'ip_address'=>$ip_address
            // 	);
            // 	$this->user_model->add_user_login_history($data_post);
    		// }

			$today_date=date("Y-m-d");
			$last_logged_in_date=$get_last_login_h['action_at_date_format'];
			if(($today_date!=$last_logged_in_date) && $get_last_login_h['action_type']=='LI')
			{
				$data_post=array(
					'user_id'=>$user_id,
					'action_at'=>$last_logged_in_date.' 23:59:59',
					'action_type'=>'LO',
					'ip_address'=>$ip_address
					);
				$this->user_model->add_user_login_history($data_post);

				$data_post=array(
					'user_id'=>$user_id,
					'action_at'=>$today_date.' 00:01:01',
					'action_type'=>'LI',
					'ip_address'=>$ip_address
					);
				$this->user_model->add_user_login_history($data_post);
			}

			if($get_last_login_h['action_type']=='LI')
			{
				$data_post=array(
					'user_id'=>$user_id,
					'action_at'=>date("Y-m-d H:i:s"),
					'action_type'=>'LO',
					'ip_address'=>$ip_address
					);
				$this->user_model->add_user_login_history($data_post);
			}			
    	}
    	else
		{
    		// $data_post=array(
			// 	'user_id'=>$user_id,
			// 	'action_at'=>date("Y-m-d H:i:s"),
			// 	'action_type'=>'LI',
			// 	'ip_address'=>$ip_address
            // 	);
            // $this->user_model->add_user_login_history($data_post);
    	}    	
    	// =========================
		
		redirect(admin_url().'login/'.$token);		
		exit;
	}	

	public function idle_logout()
	{
		$admin_session_data=$this->session->userdata('admin_session_data');	
		$user_id=$admin_session_data['user_id'];
		if($user_id){
			echo'Y';
		}
		else{
			echo'N';
		}
	}

	public function on_browser_close_logout()
	{
		$admin_session_data=$this->session->userdata('admin_session_data');	
		$user_id=$admin_session_data['user_id'];
		if($user_id){
			$get_last_login_h=$this->user_model->last_user_login_history($user_id);
			if(count($get_last_login_h))
			{
				if($get_last_login_h['action_type']=='LI')
				{
					$ip_address=$this->input->ip_address();
					$data_post=array(
						'user_id'=>$user_id,
						'action_at'=>date("Y-m-d H:i:s"),
						'action_type'=>'LO',
						'ip_address'=>$ip_address
						);
					$this->user_model->add_user_login_history($data_post);	
				}
			}	
		}
	}
}

?>