<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Site_maintenance extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
	}
	public function index()
	{		
				
		$this->load->view('admin/site_maintenance_view', $data);	
	}
}
