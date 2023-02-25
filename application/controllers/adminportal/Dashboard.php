<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	function __construct()
	{
		parent :: __construct();
		is_adminportal_logged_in();
		init_adminportal_element();
		$this->api_access_token = get_api_access_token();    
		$this->load->model(array("Client_model","User_model","Adminportal_model"));
	}

	public function index()
	{	
		$data = array();	
		
		$data['topmenu_list']=get_permission_wise_menu_list();

		$this->load->view('adminmaster/dashboard_view',$data);
	}

	
}