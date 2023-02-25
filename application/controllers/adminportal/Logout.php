<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Logout extends CI_Controller {
	public function __construct()
	{	
		parent::__construct();
		is_adminportal_logged_in();
		init_adminportal_element();	
		$this->load->model(array(''));		
	}
	public function index()
	{		
		// -------------------------------------
		$admin_session_data=$this->session->userdata('adminportal_session_data');		
		$session_array = array(
								'user_id'=>'',
								'user_type'=>'',								
								'user_name'=>'',									
								'user_email'=>'',
								'user_mobile'=>'',
								'is_adminportal_logged_in'=>FALSE
							  );
						
		$this->session->unset_userdata('adminportal_session_data',$session_array);
		// -------------------------------------	
		$msg = 'You have successfully loggedout.';
		$this->session->set_flashdata('success_msg', $msg);
		redirect(adminportal_url().'login');		
		exit;
	}	
}

?>